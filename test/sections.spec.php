<?php

use League\Plates\Template\Sections;

describe('Sections', function() {
    it('can add a section', function() {
        $sections = new Sections(['foo' => 'bar']);
        $sections->add('foo', 'foobar');
        expect($sections->get('foo'))->equal('foobar');
    });
    it('can append a section', function() {
        $sections = new Sections(['foo' => 'foo']);
        $sections->append('foo', 'bar');
        expect($sections->get('foo'))->equal('foobar');
    });
    it('can prepend a section', function() {
        $sections = new Sections(['foo' => 'bar']);
        $sections->prepend('foo', 'foo');
        expect($sections->get('foo'))->equal('foobar');
    });
    it('can clear a section', function() {
        $sections = new Sections(['foo' => 'bar']);
        $sections->clear('foo');
        expect($sections->get('foo'))->equal(null);
    });
    it('can get a section', function() {
        $sections = new Sections(['foo' => 'bar']);
        expect($sections->get('foo'))->equal('bar');
    });
    it('returns null if section does not exist', function() {
        $sections = new Sections();
        expect($sections->get('foo'))->equal(null);
    });
});
