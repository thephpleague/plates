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
    it('can get the template attributes', function() {
        $t = new Template('a', [], [2,3]);
        expect($t->attributes)->equal([2,3]);
    });
    it('can fork a child template that has a reference to parent', function() {
        $t = new Template('a', ['a' => 1, 'b' => 1], ['c' => 1]);
        $f = $t->fork('b', ['b' => 2], ['d' => 2]);
        expect($f->parent->template)->equal($t);
        expect($f->name)->equal('b');
        expect($f->data)->equal(['b' => 2]);
        expect($f->attributes)->equal(['d' => 2]);
    });
    it('can clone a template', function() {
        $t = new Template('a', ['a' => 1]);
        $t1 = clone $t;
        expect($t->reference)->not->equal($t1->reference);
        expect($t->data)->equal($t1->data);
        expect($t->name)->equal($t1->name);
    });
    it('can keep a reference', function() {
        $t = new Template('a');
        $t1 = $t->withName('b');
        $t2 = $t1->withName('c');
        expect($t->reference)->equal($t2->reference);
        expect($t)->not->equal($t2);
        expect($t->reference->template)->equal($t2);
    });
    it('can return the dereferenced parent', function() {
        $t = new Template('a');
        expect($t->parent())->null();
        expect($t->fork('b')->parent())->equal($t);
    });
    it('can update the name', function() {
        $t = new Template('a');
        $t1 = $t->withName('b');
        expect($t)->not->equal($t1);
        expect($t1->name)->equal('b');
    });
    it('can update the data', function() {
        $t = new Template('a');
        $t1 = $t->withData([1,2,3]);
        expect($t)->not->equal($t1);
        expect($t1->data)->equal([1,2,3]);
    });
    it('can update and merge the data', function() {
        $t = new Template('a', ['a' => 1, 'b' => 1]);
        $t1 = $t->withAddedData(['a' => 2]);
        expect($t)->not->equal($t1);
        expect($t1->data)->equal(['a' => 2, 'b' => 1]);
    });
    it('can update the attributes', function() {
        $t = new Template('a');
        $t1 = $t->withAttributes([1,2,3]);
        expect($t)->not->equal($t1);
        expect($t1->attributes)->equal([1,2,3]);
    });
    it('can update and merge the attributes', function() {
        $t = new Template('a', [], ['a' => 1, 'b' => 1]);
        $t1 = $t->withAddedAttributes(['a' => 2]);
        expect($t)->not->equal($t1);
        expect($t1->attributes)->equal(['a' => 2, 'b' => 1]);
    });
});
describe('Template Matchers', function() {
    describe('matchName', function() {
        it('matches off of the template normalized name', function() {
            $match = Template\matchName('foo');
            $t = new Template('', [], ['normalized_name' => 'foo']);
            expect($match($t))->equal(true);
        });
        it('matches off of the template name if the normalized name is missing', function() {
            $match = Template\matchName('foo');
            $t = new Template('foo');
            expect($match($t))->equal(true);
        });
    });
});
