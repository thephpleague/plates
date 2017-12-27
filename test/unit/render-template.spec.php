<?php

use League\Plates\{
    HydrateTemplate\CallableHydrateTemplate,
    RenderTemplate\FileSystemRenderTemplate,
    RenderTemplate\ComposeRenderTemplate,
    RenderTemplate\LayoutRenderTemplate,
    Template
};

use function League\Plates\{
    Template\withLayout,
    Template\getSections
};

describe('RenderTemplate', function() {
    beforeEach(function() {
        $this->compose = function($template) {
            return $template->withAddedData([
                'this' => $template->reference,
                'render' => $template->attributes['render']
            ])->withAddedContext(['path' => $template->name]);
        };
    });
    describe('FileSystemRenderTemplate', function() {
        it('it renders a template into string', function() {
            $rt = new FileSystemRenderTemplate(
                Template\mockInclude([
                    'foo' => function($path, $data) {
                        $content = "{$path}-{$data['a']}";
                        return $content . "-" . $data['render']->renderTemplate($data['this']()->fork(
                            'bar',
                            ['a' => 2]
                        ));
                    },
                    'bar' => function($path, $data) {
                        return "{$path}-{$data['a']}";
                    }
                ])
            );
            $rt = new ComposeRenderTemplate($rt, $this->compose);
            $t = new Template('foo', ['a' => 1], ['render' => $rt]);
            expect($rt->renderTemplate($t))->equal('foo-1-bar-2');
        });
    });
    describe('LayoutRenderTemplate', function() {
        it('recursively renders a layout template if there is one', function() {
            $rt = new FileSystemRenderTemplate(
                Template\mockInclude([
                    'main' => function($path, $data) {
                        $tpl = $data['this']->template;
                        $tpl = $tpl->with('layout', $tpl->fork('sub-layout')->reference);
                        return 'c';
                    },
                    'sub-layout' => function($path, $data) {
                        $tpl = $data['this']->template;
                        $tpl = $tpl->with('layout', $tpl->fork('layout')->reference);
                        $content = $tpl->get('sections')->get('content');
                        return "b{$content}b";
                    },
                    'layout' => function($path, $data) {
                        $content = $data['this']()->get('sections')->get('content');
                        return "a{$content}a";
                    },
                ])
            );
            $rt = new LayoutRenderTemplate($rt);
            $rt = new ComposeRenderTemplate($rt, $this->compose);
            $res = $rt->renderTemplate(new Template('main', [], ['render' => $rt]));
            expect($res)->equal('abcba');
        });
        xit('initializes the sections on templates being rendered');
    });
});
