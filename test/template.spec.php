<?php

use League\Plates\{
    Content\StringContent,
    Template
};

describe('Template', function() {
    it('can get the template name', function() {
        $t = new Template('a');
        expect($t->getName())->equal('a');
    });
    it('can get the template data', function() {
        $t = new Template('a', [1,2]);
        expect($t->getData())->equal([1,2]);
    });
    it('can get the template context', function() {
        $t = new Template('a', [], [2,3]);
        expect($t->getContext())->equal([2,3]);
    });
    it('can get the template content', function() {
        $t = new Template('a', [], [], new StringContent('content'));
        expect((string) $t->getContent())->equal('content');
    });
    it('can update the template name', function() {
        $t1 = new Template('');
        $t2 = $t1->withName('a');
        expect($t2->getName())->equal('a');
        expect($t2)->not->equal($t1);
    });
    it('can update the template data', function() {
        $t1 = new Template('');
        $t2 = $t1->withData([1,2]);
        expect($t2->getData())->equal([1,2]);
        expect($t2)->not->equal($t1);
    });
    it('can update the template context', function() {
        $t1 = new Template('');
        $t2 = $t1->withContext([1]);
        expect($t2->getContext())->equal([1]);
        expect($t2)->not->equal($t1);
    });
    it('can update the template content', function() {
        $t1 = new Template('');
        $t2 = $t1->withContent(new StringContent('content'));
        expect((string) $t2->getContent())->equal('content');
        expect($t2)->not->equal($t1);
    });
});
