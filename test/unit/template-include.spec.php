<?php

use League\Plates\{
    Content\StringContent,
    Template,
    Exception\PlatesException
};

describe('phpInclude', function() {
    it('it includes a file and returns the output buffered contents', function() {
        $inc = Template\phpInclude();
        $res = $inc(__DIR__ . '/fixtures/simple/main.phtml', ['name' => 'foo']);
        expect(trim($res))->equal("<div>foo</div>");
    });
});
describe('mockInclude', function() {
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
