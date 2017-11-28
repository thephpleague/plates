<?php

use League\Plates\{
    RenderTemplate\PlatesRenderTemplate,
    Template
};

describe('PlatesRenderTemplate', function() {
    it('it renders a template into string', function() {
        $rt = new PlatesRenderTemplate(
            null,
            null,
            function($path, $data) {
                $content = "{$path}-{$data['a']}";
                if ($path == 'bar') {
                    return $content;
                }

                return $content . "-" . $data['v']->render->renderTemplate(new Template(
                    'bar',
                    ['a' => 2]
                ));
            }
        );
        $t = new Template('foo', ['a' => 1]);
        expect($rt->renderTemplate($t))->equal('foo-1-bar-2');
    });
});
