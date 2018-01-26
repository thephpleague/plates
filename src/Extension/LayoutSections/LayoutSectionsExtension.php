<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;
use League\Plates\Extension\RenderContext;

final class LayoutSectionsExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();

        $c->wrap('renderTemplate.factories', function($factories, $c) {
            $default_layout_path = $c->get('config')['default_layout_path'];
            if ($default_layout_path) {
                $factories[] = DefaultLayoutRenderTemplate::factory($default_layout_path);
            }
            $factories[] = LayoutRenderTemplate::factory();
            return $factories;
        });

        $plates->defineConfig(['default_layout_path' => null]);
        $plates->pushComposers(function($c) {
            return ['layoutSections.sections' => sectionsCompose()];
        });
        $plates->addFuncs(function($c) {
            $template_args = RenderContext\assertTemplateArgsFunc();
            $one_arg = RenderContext\assertArgsFunc(1);

            return [
                'layout' => [layoutFunc(), $template_args],
                'section' => [sectionFunc(), RenderContext\assertArgsFunc(1, 1)],
                'start' => [startFunc(), $one_arg],
                'push' => [startFunc(START_APPEND), $one_arg],
                'unshift' => [startFunc(START_PREPEND), $one_arg],
            ];
        });
    }
}
