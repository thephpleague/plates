<?php

use League\Plates\{
    RenderTemplate\PlatesRenderTemplate,
    RenderTemplate\LayoutRenderTemplate,
    Template
};

use function League\Plates\{
    Template\setLayout,
    Template\getSections
};

describe('PlatesRenderTemplate', function() {
    it('it renders a template into string', function() {
        $rt = new PlatesRenderTemplate(
            null,
            null,
            Template\mockInclude([
                'foo' => function($path, $data) {
                    $content = "{$path}-{$data['a']}";
                    return $content . "-" . $data['v']->render->renderTemplate(new Template(
                        'bar',
                        ['a' => 2]
                    ));
                },
                'bar' => function($path, $data) {
                    return "{$path}-{$data['a']}";
                }
            ])
        );
        $t = new Template('foo', ['a' => 1]);
        expect($rt->renderTemplate($t))->equal('foo-1-bar-2');
    });
});
describe('LayoutRenderTemplate', function() {
    it('recursively renders a layout template if there is one', function() {
        $rt = new PlatesRenderTemplate(
            null,
            null,
            Template\mockInclude([
                'main' => function($path, $data) {
                    setLayout($data['v']->template, new Template('sub-layout'));
                    return 'c';
                },
                'sub-layout' => function($path, $data) {
                    setLayout($data['v']->template, new Template('layout'));
                    $content = getSections($data['v']->template)->get('content');
                    return "b{$content}b";
                },
                'layout' => function($path, $data) {
                    $content = getSections($data['v']->template)->get('content');
                    return "a{$content}a";
                },
            ])
        );
        $rt = new LayoutRenderTemplate($rt);
        $res = $rt->renderTemplate(new Template('main'));
        expect($res)->equal('abcba');
    });
});
