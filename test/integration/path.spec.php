<?php

use League\Plates;

describe('Path Extension', function() {
    it('will normalize any path type names', function() {
        $plates = Plates\Engine::create(__DIR__ . '/fixtures/normalize-name');
        $plates->addGlobals(['name' => 'Bar']);
        $plates->addData(['name' => 'Foo'], 'main');

        $expected = "name: Foo";
        expect($plates->render(__DIR__ . '/fixtures/normalize-name/main'))->equal($expected);
    });
    it('allows empty basedir', function() {
        $plates = Plates\Engine::createWithConfig([]);
        expect($plates->render(__DIR__ . '/fixtures/simple/main'))->equal("foo\n");
    });
});
