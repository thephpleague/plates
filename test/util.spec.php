<?php

use League\Plates\{
    Exception\ComposeException,
    Util
};

describe('Util/id', function() {
    it('returns the argument given', function() {
        expect(Util\id()(1))->equal(1);
    });
    it ('can return multiple arguments if multi = true', function() {
        expect(Util\id(true)(1, 2))->equal([1,2]);
    });
});
describe('Util/stack', function() {
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
        })->to->throw(ComposeException::class);
    });
});
xdescribe('Util/stackGroup', function() {

});
describe('Util/compose', function() {
    it('can compose functions together', function() {
        $handler = Util\compose([
            function($v) { return $v + 1; },
            function($v) { return $v * 2; }
        ]);
        expect($handler(1))->equal(4);
    });
    it('can compose functions with multiple arguments', function() {
        $handler = Util\compose([
            function($a, $b) {
                return [$a + $b, $b - $a];
            },
            function($a, $b) {
                return [$a * $b, $b * $a];
            }
        ], true);
        expect($handler(1, 2))->equal([3, 3]);
    });
});
describe('Util/joinPath', function() {
    it('joins paths together', function() {
        expect(Util\joinPath(['a', 'b'], '/'))->equal('a/b');
    });
    it('properly trims each component', function() {
        expect(Util\joinPath(['/a/', '/b/'], '/'))->equal('/a/b/');
    });
});
