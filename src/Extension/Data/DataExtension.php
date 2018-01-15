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

        $plates->unshiftComposers(function($c) {
            return array_filter([
                'data.perTemplateData' => $c->get('data.template_data') ? perTemplateDataCompose($c->get('data.template_data')) : null,
                'data.mergeParentData' => $c->get('config')['merge_parent_data'] ? mergeParentDataCompose() : null,
                'data.addGlobals' => $c->get('data.globals') ? addGlobalsCompose($c->get('data.globals')) : null,
            ]);
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
