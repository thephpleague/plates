<?php

namespace League\Plates;

final class PlatesExtension implements Extension
{
    public function register(Engine $plates) {
        $c = $plates->getContainer();

        $c->add('config', [
            'validate_paths' => true,
            'php_extensions' => ['php', 'phtml'],
            'image_extensions' => ['png', 'jpg'],
        ]);
        $c->addComposed('compose', function() { return []; });
        $c->add('fileExists', function($c) {
            return 'file_exists';
        });
        $c->add('renderTemplate', function($c) {
            $rt = new RenderTemplate\FileSystemRenderTemplate([
                [
                    Template\matchExtensions($c->get('config')['php_extensions']),
                    new RenderTemplate\PhpRenderTemplate($c->get('renderTemplate.bind'))
                ],
                [
                    Template\matchExtensions($c->get('config')['image_extensions']),
                    RenderTemplate\MapContentRenderTemplate::base64Encode(new RenderTemplate\StaticFileRenderTemplate())
                ],
                [
                    Template\matchStub(true),
                    new RenderTemplate\StaticFileRenderTemplate(),
                ]
            ]);
            if ($c->get('config')['validate_paths']) {
                $rt = new RenderTemplate\ValidatePathRenderTemplate($rt, $c->get('fileExists'));
            }
            $rt = array_reduce($c->get('renderTemplate.factories'), function($rt, $create) {
                return $create($rt);
            }, $rt);
            $rt = new RenderTemplate\ComposeRenderTemplate($rt, $c->get('compose'));
            return $rt;
        });
        $c->add('renderTemplate.bind', function() {
            return Util\id();
        });
        $c->add('renderTemplate.factories', function() {
            return [];
        });

        $plates->addMethods([
            'pushComposers' => function(Engine $e, $def_composer) {
                $e->getContainer()->wrapComposed('compose', function($composed, $c) use ($def_composer) {
                    return array_merge($composed, $def_composer($c));
                });
            },
            'unshiftComposers' => function(Engine $e, $def_composer) {
                $e->getContainer()->wrapComposed('compose', function($composed, $c) use ($def_composer) {
                    return array_merge($def_composer($c), $composed);
                });
            },
            'addConfig' => function(Engine $e, array $config) {
                $e->getContainer()->merge('config', $config);
            },
            /** merges in config values, but will defer to values already set in the config */
            'defineConfig' => function(Engine $e, array $config_def) {
                $config = $e->getContainer()->get('config');
                $e->getContainer()->add('config', array_merge($config_def, $config));
            },
        ]);
    }
}
