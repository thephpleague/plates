<?php

use League\Plates\{
    Template
};

describe('getLayout', function() {
    it('can get a layout or null from a template', function() {
        $t = new Template('main');
        $layout = new Template('layout');
        Template\setLayout($t, $layout);
        expect(Template\getLayout($t))->equal($layout);
        expect(Template\getLayout($layout))->equal(null);
    });
});
describe('getSections', function() {
    it('can get sections from a template', function() {
        $t = new Template('main');
        $sections = new Template\Sections();
        Template\setSections($t, $sections);
        expect(Template\getSections($t))->equal($sections);
    });
    it('can initialize sections for a template if non exist', function() {
        $t = new Template('main');
        expect(Template\getSections($t))->instanceof(Template\Sections::class);
        expect(Template\getSections($t))->equal(Template\getSections($t));
    });
});
