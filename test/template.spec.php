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

    describe('#phpInclude', function() {
        it('it includes a file and returns the output buffered contents', function() {
            $inc = Template\phpInclude();
            $res = $inc(__DIR__ . '/fixtures/simple/main.phtml', ['name' => 'foo']);
            expect(trim($res))->equal("<div>foo</div>");
        });
    });
    describe('#mockInclude', function() {
        it('provides a simple mocking system for includes', function() {
            $inc = Template\mockInclude(['foo' => function($path, $params) {
                return $params['v'];
            }]);
            expect($inc('foo', ['v' => 'bar']))->equal('bar');
        });
        it('throws an exception if no mock is found for path', function() {
            $inc = Template\mockInclude([]);
            expect(function() use ($inc) {
                $inc('foo', []);
            })->to->throw(PlatesException::class);
        });
    });
});
