<?php

use League\Plates\{
    Content,
    Template
};

xdescribe('StringContent', function() {
    it('can create content from a stringy value', function() {
        $content = new Content\StringContent('a');
        expect((string) $content)->equal('a');
    });
    it('can create an empty value', function() {
        $content = Content\StringContent::empty();
        expect((string) $content)->equal('');
    });
});
xdescribe('LazyContent', function() {
    it('can create content lazily', function() {
        $called = 0;
        $content = new Content\LazyContent(function() use (&$called) {
            $called += 1;
            return 'a';
        });
        expect($called)->equal(0);
        expect((string) $content)->equal('a');
        expect((string) $content)->equal('a');
        expect($called)->equal(1);
    });
    it('can create from a template', function() {
        $content = Content\LazyContent::fromTemplate(
            new Template('', [], [], new Content\StringContent('a'))
        );
        expect((string) $content)->equal('a');
    });
});
xdescribe('CollectionContent', function() {
    it('can create a collection of content appended to one another', function() {
        $content = new Content\CollectionContent([
            new Content\StringContent('a'),
            new Content\StringContent('b'),
            new Content\StringContent('c'),
        ]);
        expect((string) $content)->equal('abc');
    });
});
