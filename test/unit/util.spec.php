<?php

use League\Plates\{
    Exception\StackException,
    Util
};

describe('Util', function() {
    describe('id', function() {
        it('returns the argument given', function() {
            expect(Util\id()(1))->equal(1);
        });
    });
    describe('stack', function() {
        it('can stack a group of functions', function() {
            $handler = Util\stack([
                function($v, $next) {
                    return $next($v + 1) * 2;
                },
                function($v) {
                    return $v;
                }
            ]);
            expect($handler(2))->equal(6);
        });
    it('throws an exception if no handler returns a result', function() {
            $handler = Util\stack([]);
            expect(function() use ($handler) {
                $handler();
            })->to->throw(StackException::class);
        });
    });
    describe('stackGroup', function() {
        it('groups a stack functions to be used in a stack', function() {
            $add = function($by) {
                return function($v, $next) use ($by) {
                    return $next($v + $by);
                };
            };
            $group = Util\stackGroup([
                $add(1),
                $add(2)
            ]);
            $stack = Util\stack([$group, Util\id()]);
            expect($stack(0))->equal(3);
        });
    });
    describe('prioritizeStacks', function() {
        it('prioritizes stacks by index', function() {
            $stacks = [
                1 => [3],
                -1 => [1],
                0 => [2],
            ];
            expect(Util\sortStacks($stacks))->equal([1,2,3]);
        });
    });
    describe('joinPath', function() {
        it('joins paths together', function() {
            expect(Util\joinPath(['a', 'b'], '/'))->equal('a/b');
        });
        it('properly trims each component', function() {
            expect(Util\joinPath(['/a/', '/b/'], '/'))->equal('/a/b/');
        });
    });
    describe('debugType', function() {
        it('returns the type of the value', function() {
            expect(Util\debugType(''))->equal('string');
        });
        it('returns the class name if object', function() {
            expect(Util\debugType(new stdClass()))->equal('object stdClass');
        });
    });
});
