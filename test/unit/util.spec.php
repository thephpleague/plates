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
                Util\id(),
                function($v, $next) {
                    return $next($v + 1) * 2;
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
                $add(2),
                $add(1),
            ]);
            $stack = Util\stack([Util\id(), $group]);
            expect($stack(0))->equal(3);
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
    describe('when', function() {
        it('invokes the callable with the argument if the predicate returns true', function() {
            $maybeSquare = Util\when(function($v) { return $v > 5; }, function($v) {
                return $v * $v;
            });
            expect($maybeSquare(6))->equal(36);
        });
        it('returns the value if the predicate returns false', function() {
            $maybeSquare = Util\when(function($v) { return $v > 5; }, function($v) {
                return $v * $v;
            });
            expect($maybeSquare(5))->equal(5);
        });
    });
});
