<?php

use League\Plates\{
    RenderTemplate,
    Template,
    Extension\RenderContext\FuncArgs,
    Exception\FuncException
};

use function Eloquent\Phony\{on, mock};
use function League\Plates\{
    Util\stack
};
use function League\Plates\Extension\RenderContext\{
    startBufferFunc,
    endFunc,
    slotFunc,
    componentFunc
};

describe('Extension\RenderContext', function() {
    xdescribe('FuncArgs', function() {
        it('can store func arguments');
        it('can update the name');
        it('can update the args');
    });
    describe('Funcs', function() {
        beforeEach(function() {
            $this->render = mock(RenderTemplate::class)->get();
            $this->template = (new Template('', []))->reference;
            $this->args = new FuncArgs($this->render, $this->template, '', []);
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
                expect($args->template()->get('buffer_stack'))->length(1);
                expect($args->template()->get('buffer_stack')[0])->equal([
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
                expect($this->args->template()->get('buffer_stack'))->length(0);
            });
        });
        describe('slotFunc', function() {
            it('throws an exception if not component slot data is there', function() {
                expect(function() {
                    slotFunc()($this->args);
                })->throw(FuncException::class, 'Cannot call slot func outside of component definition.');
            });
            it('adds to the component slot data', function() {
                $this->args->template()->with('component_slot_data', []);
                slotFunc()($this->args->withArgs(['foo']));
                echo "bar";
                endFunc()($this->args);
                expect($this->args->template()->get('component_slot_data')['foo'])
                    ->equal('bar');
            });
        });
        describe('componentFunc', function() {
            it('inserts a partial while passing in buffered content', function() {
                $insert_args = null;
                componentFunc(function($args) use (&$insert_args) {
                    $insert_args = $args->args;
                })($this->args->withArgs(['name', ['a' => 1]]));
                $this->args->template()->attributes['component_slot_data']['b'] = 2;
                echo "content";
                endFunc()($this->args);
                expect($insert_args)->equal([
                    'name',
                    [
                        'a' => 1,
                        'slot' => 'content',
                        'b' => 2,
                    ]
                ]);
            });
            it('throws an exception if the nested component func is called', function() {
                expect(function() {
                    componentFunc()($this->args->withArgs(['name', []]));
                    componentFunc()($this->args->withArgs(['name', []]));
                })->throw(FuncException::class, 'Cannot nest component func calls.');
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
});
