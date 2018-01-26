<?php

use League\Plates\Extension\LayoutSections\{
    LayoutRenderTemplate,
    Sections
};
use League\Plates\{
    Template,
    RenderTemplate\MockRenderTemplate,
    Extension\RenderContext\FuncArgs
};
use const League\Plates\Extension\LayoutSections\{
    START_APPEND,
    START_PREPEND,
    START_REPLACE
};
use function League\Plates\Extension\LayoutSections\{
    startFunc,
    sectionFunc
};
use function League\Plates\Extension\RenderContext\{endFunc};


describe('Extension\LayoutSections', function() {
    describe('Sections', function() {
        it('can add a section', function() {
            $sections = new Sections(['foo' => 'bar']);
            $sections->add('foo', 'foobar');
            expect($sections->get('foo'))->equal('foobar');
        });
        it('can append a section', function() {
            $sections = new Sections(['foo' => 'foo']);
            $sections->append('foo', 'bar');
            expect($sections->get('foo'))->equal('foobar');
        });
        it('can prepend a section', function() {
            $sections = new Sections(['foo' => 'bar']);
            $sections->prepend('foo', 'foo');
            expect($sections->get('foo'))->equal('foobar');
        });
        it('can clear a section', function() {
            $sections = new Sections(['foo' => 'bar']);
            $sections->clear('foo');
            expect($sections->get('foo'))->equal(null);
        });
        it('can get a section', function() {
            $sections = new Sections(['foo' => 'bar']);
            expect($sections->get('foo'))->equal('bar');
        });
        it('returns null if section does not exist', function() {
            $sections = new Sections();
            expect($sections->get('foo'))->equal(null);
        });
    });

    describe('LayoutRenderTemplate', function() {
        it('recursively renders a layout template if there is one', function() {
            $rt = new MockRenderTemplate([
                'main' => function($template) {
                    $template->with('layout', $template->fork('sub-layout')->reference);
                    return 'c';
                },
                'sub-layout' => function($template) {
                    $template = $template->with('layout', $template->fork('layout')->reference);
                    $content = $template->get('sections')->get('content');
                    return "b{$content}b";
                },
                'layout' => function($template) {
                    return "a{$template->get('sections')->get('content')}a";
                },
            ]);
            $rt = new LayoutRenderTemplate($rt);
            expect($rt->renderTemplate(new Template('main', [], ['sections' => new Sections()])))->equal('abcba');
        });
    });

    describe('Funcs', function() {
        beforeEach(function() {
            $this->render = new MockRenderTemplate([]);
            $this->template = (new Template('', [], ['sections' => new Sections()]))->reference;
            $this->args = new FuncArgs($this->render, $this->template, '', []);
        });
        xdescribe('layoutFunc', function() {
            it('forks a template and sets the layout');
        });
        describe('sectionFunc', function() {
            it('gets a section from the template sections', function() {
                ($this->template)()->get('sections')->add('foo', 'bar');
                $res = sectionFunc()($this->args->withArgs(['foo', null]));
                expect($res)->equal('bar');
            });
            it('returns an empty section if no else is defined', function() {
                $res = sectionFunc()($this->args->withArgs(['foo', null]));
                expect($res)->equal(null);
            });
            it('returns the else content if no section is defined', function() {
                $res = sectionFunc()($this->args->withArgs(['foo', 'baz']));
                expect($res)->equal('baz');
            });
            it('invokes the else callable if no section is defined', function() {
                $res = sectionFunc()($this->args->withArgs(['foo', function() {
                    echo 'baz';
                }]));
                expect($res)->equal('baz');
            });
        });
        describe('startFunc', function() {
            $create_test = function($update_text, $update, $expected) {
                it("can start buffering and {$update_text} the section", function() use ($update, $expected) {
                    ($this->template)()->get('sections')->add('foo', 'baz');
                    startFunc($update)($this->args->withArgs(['foo']));
                    echo "bar";
                    endFunc()($this->args);
                    expect(($this->template)()->get('sections')->get('foo'))->equal($expected);
                });
            };
            $create_test('replace', START_REPLACE, 'bar');
            $create_test('append', START_APPEND, 'bazbar');
            $create_test('prepend', START_PREPEND, 'barbaz');
        });
    });
});
