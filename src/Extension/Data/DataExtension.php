<?php

namespace League\Plates\Extension\Data;

use League\Plates;

/** The DataExtension adds the ability to hydrate data into a template before it gets rendered. */
final class DataExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->add('data.globals', []);
        $c->add('data.template_data', []);
        $c->merge('config', ['merge_parent_data' => true]);
        $c->wrap('compose', function($compose, $c) {
            return Plates\Util\compose(...array_filter([
                $c->get('config')['merge_parent_data'] ? mergeParentDataCompose() : null,
                $c->get('data.globals') ? globalResolveData($c->get('data.globals')) : null,
                $c->get('data.template_data') ? perTemplateResolveData($c->get('data.template_data')) : null,
                $compose
            ]));
        });

        $plates->addMethods([
            'addGlobals' => function(Plates\Engine $e, array $data) {
                $c = $e->getContainer();
                $c->merge('data.globals', $data);
            },
            'addGlobal' => function(Plates\Engine $e, $name, $value) {
                $e->getContainer()->merge('data.globals', [$name => $value]);
            },
            'assignTemplateData' => function(Plates\Engine $e, $name, $data) {
                $template_data = $e->getContainer()->get('data.template_data');
                if (!isset($template_data[$name])) {
                    $template_data[$name] = [];
                }
                $template_data[$name] = array_merge($template_data[$name], $data);
                $e->getContainer()->add('data.template_data', $template_data);
            }
        ]);
    }
}
