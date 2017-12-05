<?php

use League\Plates\{
    Content\StringContent,
    Template,
    Exception\PlatesException
};

describe('Template', function() {
    it('can get the template name', function() {
        $t = new Template('a');
        expect($t->name)->equal('a');
    });
    it('can get the template data', function() {
        $t = new Template('a', [1,2]);
        expect($t->data)->equal([1,2]);
    });
    it('can get the template context', function() {
        $t = new Template('a', [], [2,3]);
        expect($t->context)->equal([2,3]);
    });
    it('can fork a template', function() {
        $t = new Template('a', ['a' => 1, 'b' => 1], ['c' => 1]);
        $f = $t->fork('b', ['b' => 2], ['d' => 2]);
        expect($f)->not->equal($t);
        expect($f->name)->equal('b');
        expect($f->data)->equal(['a' => 1, 'b' => 2]);
        expect($f->context)->equal(['c' => 1, 'd' => 2]);
    });
});
