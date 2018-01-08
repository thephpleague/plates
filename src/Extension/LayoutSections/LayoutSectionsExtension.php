<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;
use League\Plates\Extension\RenderContext;

final class LayoutSectionsExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();

        $c->wrap('compose', function($compose, $c) {
            return Plates\Util\compose($compose, sectionsCompose());
        });
        $c->wrap('renderTemplate.factories', function($factories) {
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
