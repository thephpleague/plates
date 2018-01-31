<?php

namespace League\Plates\Extension\Path;

use League\Plates;

final class PathExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->add('path.resolvePath.prefixes', function($c) {
            $config = $c->get('config');

            // wrap base dir in an array if not already
            $base_dir = isset($config['base_dir']) ? $config['base_dir'] : null;
            $base_dir = $base_dir ? (is_string($base_dir) ? [$base_dir] : $base_dir) : $base_dir;
            return $base_dir;
        });
        $c->addComposed('path.normalizeName', function($c) {
            return [
                'path.stripExt' => stripExtNormalizeName(),
                'path.stripPrefix' => stripPrefixNormalizeName($c->get('path.resolvePath.prefixes'))
            ];
        });
        $c->addStack('path.resolvePath', function($c) {
            $config = $c->get('config');
            $prefixes = $c->get('path.resolvePath.prefixes');
            return array_filter([
                'path.id' => idResolvePath(),
                'path.prefix' => $prefixes ? prefixResolvePath($prefixes, $c->get('fileExists')) : null,
                'path.ext' => isset($config['ext']) ? extResolvePath($config['ext']) : null,
                'path.relative' => relativeResolvePath(),
            ]);
        });
        $plates->defineConfig([
            'ext' => 'phtml',
            'base_dir' => null,
        ]);
        $plates->pushComposers(function($c) {
            return [
                'path.normalizeName' => normalizeNameCompose($c->get('path.normalizeName')),
                'path.resolvePath' => resolvePathCompose($c->get('path.resolvePath')),
            ];
        });
    }
}
