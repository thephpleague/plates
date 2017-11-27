<?php

use League\Plates\{
    Content\StringContent,
    Template
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
    it('can get the template content', function() {
        $t = new Template('a', [], [], new StringContent('content'));
        expect((string) $t->content)->equal('content');
    });
});
