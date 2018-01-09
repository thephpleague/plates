<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;
use League\Plates\Extension\RenderContext;

final class LayoutSectionsExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();

        $c->merge('config', ['default_layout_path' => null]);
        $c->wrapComposed('compose', function($composed, $c) {
            return array_merge($composed, ['layoutSections.sections' => sectionsCompose()]);
        });
        $c->wrap('renderTemplate.factories', function($factories, $c) {
            $default_layout_path = $c->get('config')['default_layout_path'];
            if ($default_layout_path) {
                $factories[] = DefaultLayoutRenderTemplate::factory($default_layout_path);
            }
            $factories[] = LayoutRenderTemplate::factory();
            return $factories;
        });
        $c->wrap('renderContext.func.funcs', function($funcs, $c) {
            $template_args = RenderContext\assertTemplateArgsFunc();
            $one_arg = RenderContext\assertArgsFunc(1);

            return array_merge($funcs, [
                'layout' => [$template_args, layoutFunc()],
                'section' => [$one_arg, sectionFunc()],
                'start' => [$one_arg, startFunc()],
                'push' => [$one_arg, startFunc(START_APPEND)],
                'unshift' => [$one_arg, startFunc(START_PREPEND)],
            ]);
        });
    }
}
