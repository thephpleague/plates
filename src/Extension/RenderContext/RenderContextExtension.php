<?php

namespace League\Plates\Extension\RenderContext;

use League\Plates;

/** The render context extension provides a RenderContext object and functions to be used within the render context object. This RenderContext object is injected into the template data to allow usefulness in the templates. */
final class RenderContextExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->addStack('renderContext.func', function($c) {
            return [
                'notFound' => notFoundFunc(),
                'plates' => Plates\Util\stackGroup([
                    splitByNameFunc($c->get('renderContext.func.funcs')),
                    aliasNameFunc($c->get('renderContext.func.aliases')),
                ]),
            ];
        });
        $c->add('renderContext.func.aliases', [
            'e' => 'escape',
            '__invoke' => 'escape',
            'stop' => 'end',
        ]);
        $c->add('renderContext.func.funcs', function($c) {
            $template_args = assertTemplateArgsFunc();
            $one_arg = assertArgsFunc(1);
            $config = $c->get('config');

            return [
                'insert' => [insertFunc(), $template_args],
                'escape' => [
                    isset($config['escape_flags'], $config['escape_encoding'])
                        ? escapeFunc($config['escape_flags'], $config['escape_encoding'])
                        : escapeFunc(),
                    $one_arg,
                ],
                'data' => [templateDataFunc(), assertArgsFunc(0, 1)],
                'name' => [accessTemplatePropFunc('name')],
                'context' => [accessTemplatePropFunc('context')],
                'component' => [componentFunc(), $template_args],
                'slot' => [slotFunc(), $one_arg],
                'end' => [endFunc()]
            ];
        });
        $c->add('include.bind', function($c) {
            return renderContextBind($c->get('config')['render_context_var_name']);
        });
        $c->add('renderContext.factory', function($c) {
            return RenderContext::factory(
                function() use ($c) { return $c->get('renderTemplate'); },
                $c->get('renderContext.func')
            );
        });

        $plates->defineConfig([
            'render_context_var_name' => 'v',
            'escape_encoding' => null,
            'escape_flags' => null,
        ]);
        $plates->pushComposers(function($c) {
            return [
                'renderContext.renderContext' => renderContextCompose(
                    $c->get('renderContext.factory'),
                    $c->get('config')['render_context_var_name']
                )
            ];
        });

        $plates->addMethods([
            'registerFunction' => function(Plates\Engine $e, $name, callable $func, callable $assert_args = null, $simple = true) {
                $c = $e->getContainer();
                $func = $simple ? wrapSimpleFunc($func) : $func;

                $c->wrap('renderContext.func.funcs', function($funcs, $c) use ($name, $func, $assert_args) {
                    $funcs[$name] = $assert_args ? [$assert_args, $func] : [$func];
                    return $funcs;
                });
            },
            'addFuncs' => function(Plates\Engine $e, callable $add_funcs, $simple = false) {
                $e->getContainer()->wrap('renderContext.func.funcs', function($funcs, $c) use ($add_funcs, $simple) {
                    $new_funcs = $simple
                        ? array_map(wrapSimpleFunc::class, $add_funcs($c))
                        : $add_funcs($c);
                    return array_merge($funcs, $new_funcs);
                });
            },
            'wrapFuncs' => function(Plates\Engine $e, callable $wrap_funcs) {
                $e->getContainer()->wrap('renderContext.func.funcs', $wrap_funcs);
            }
        ]);
    }
}
