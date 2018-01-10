<?php

use League\Plates;

describe('Path Extension', function() {
    it('will normalize any path type names', function() {
        $plates = new Plates\Engine([
            'base_dir' => __DIR__ . '/fixtures/normalize-name'
        ]);
        $plates->addGlobals(['name' => 'Bar']);
        $plates->assignTemplateData('main', ['name' => 'Foo']);

        $expected = "name: Foo";
        expect($plates->render(__DIR__ . '/fixtures/normalize-name/main'))->equal($expected);
    });
});
