<?php

use League\Plates\{
    RenderTemplate\FileSystemRenderTemplate,
    RenderTemplate\ComposeRenderTemplate,
    RenderTemplate\MockRenderTemplate,
    Template,
    Exception\RenderTemplateException
};

use function League\Plates\{
    Template\matchStub
};

describe('RenderTemplate', function() {
    describe('MockRenderTemplate', function() {
        it('calls a mock render function based off of the template name', function() {
            $rt = new MockRenderTemplate([
                'a' => function($t) {
                    return 'a-contents';
                }
            ]);
            expect($rt->renderTemplate(new Template('a')))->equal('a-contents');
        });
        it('throws an exception if a mock render does not exist', function() {
            expect(function() {
                $rt = new MockRenderTemplate([]);
                $rt->renderTemplate(new Template('a'));
            })->throw(RenderTemplateException::class);
        });
    });
    describe('FileSystemRenderTemplate', function() {
        it('it delegates rendering to the matched renderer according to the render set', function() {
            $rt = new FileSystemRenderTemplate([
                [matchStub(false), new MockRenderTemplate(['a' => function() { return 'a'; }])],
                [matchStub(true), new MockRenderTemplate(['a' => function() { return 'b'; }])],
            ]);

            expect($rt->renderTemplate(new Template('a')))->equal('b');
        });
        it('throws an exception if no renderer is available', function() {
            expect(function() {
                $rt = new FileSystemRenderTemplate([]);
                $rt->renderTemplate(new Template('a'));
            })->throw(RenderTemplateException::class);
        });
    });
});
