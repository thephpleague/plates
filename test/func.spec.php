<?php

use League\Plates\{
    RenderTemplate,
    Template,
    RenderContext\FuncArgs,
    Exception\FuncException
};

use function Eloquent\Phony\{on, mock};
use function League\Plates\{
    Template\getSections,
    Util\stack
};
use function League\Plates\RenderContext\{
    startFunc,
    startBufferFunc,
    endFunc
};
use const League\Plates\RenderContext\{
    START_APPEND,
    START_PREPEND,
    START_REPLACE
};

xdescribe('FuncArgs', function() {
    it('can store func arguments');
    it('can update the name');
    it('can update the args');
});
describe('Funcs', function() {
    beforeEach(function() {
        $this->render = mock(RenderTemplate::class)->get();
        $this->template = new Template('');
        $this->args = new FuncArgs($this->render, $this->template, '', []);
    });
    describe('layoutFunc', function() {
        it('forks a template and sets the layout');
    });
    xdescribe('sectionFunc', function() {
        it('gets a section from the template sections');
    });
    describe('startFunc', function() {
        $create_test = function($update_text, $update, $expected) {
            it("can start buffering and {$update_text} the section", function() use ($update, $expected) {
                getSections($this->template)->add('foo', 'baz');
                startFunc($update)($this->args->withArgs(['foo']));
                echo "bar";
                endFunc()($this->args);
                expect($this->template->context['sections']->get('foo'))->equal($expected);
            });
        };
        $create_test('replace', START_REPLACE, 'bar');
        $create_test('append', START_APPEND, 'bazbar');
        $create_test('prepend', START_PREPEND, 'barbaz');
    });
    describe('startBufferFunc', function() {
        it('starts the output buffering and appends the buffer_stack', function() {
            $args = $this->args;
            $callback = function() {};

            $cur_level = ob_get_level();
            startBufferFunc(function($passed_args) use ($args, $callback) {
                expect($passed_args)->equal($args);
                return $callback;
            })($args);
            expect($args->template->context['buffer_stack'])->length(1);
            expect($args->template->context['buffer_stack'][0])->equal([
                $cur_level + 1,
                $callback
            ]);
            ob_end_clean();
        });
    });
    describe('endFunc', function() {
        it('throws an exception if no buffer_stack have been defined', function() {
            $func = endFunc();
            expect(function() use ($func) {
                $func($this->args);
            })->throw(FuncException::class, 'Cannot end a section definition because no section has been started.');
        });
        it('throws an exception if the output buffering level does not match the section def', function() {

            startBufferFunc(function() {
                return function() {};
            })($this->args);

            ob_end_clean();

            $func = endFunc();
            expect(function() use ($func) {
                $func($this->args);
            })->throw(FuncException::class, 'Output buffering level does not match when section was started.');
        });
        it('cleans the buffer and calls the callback', function() {
            $was_called = false;
            startBufferFunc(function() use (&$was_called) {
                return function() use (&$was_called) {
                    $was_called = true;
                };
            })($this->args);

            expect($was_called)->false();

            endFunc()($this->args);
            expect($was_called)->true();
            expect($this->args->template->context['buffer_stack'])->length(0);
        });
    });
    xdescribe('insertFunc', function() {
        it('forks a template and echos the rendered contents');
    });
    xdescribe('escapeFunc', function() {
        it('it escapes the content with htmlspecialchars');
    });
    xdescribe('assertArgsFunc', function() {
        it('throws an exception if the num of required args do not exist');
        it('passes through if there are enough args');
        it('appends null to the args to make up the amount of required and defaulted args');
    });
    xdescribe('aliasNameFunc', function() {
        it('passes through if no aliases are matched');
        it('recursively aliases func names');
    });
    xdescribe('splitByNameFunc', function() {
        it('allows stacks to be invoked by func name');
        it('passes through if no stack was registered per func name');
    });
    xdescribe('platesFunc', function() {
        it('creates the default plates func stack');
    });
});
