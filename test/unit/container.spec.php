<?php

use League\Plates\Util\Container;

describe('Container', function() {
    it('can add values', function() {
        $c = new Container();
        $c->add('a', 1);
        expect($c->get('a'))->equal(1);
    });
    it('can add services', function() {
        $c = new Container();
        $c->add('a', function() { return 1; });
        expect($c->get('a'))->equal(1);
    });
    it('can access the container in services', function() {
        $c = new Container();
        $c->add('a', 1);
        $c->add('b', function($c) { return $c->get('a') + 1; });
        expect($c->get('b'))->equal(2);
    });
    it('can see if an entry exists', function() {
        $c = new Container();
        expect($c->has('a'))->false;
        $c->add('a', 1);
        expect($c->has('a'))->true;
    });
    it('can wrap services', function() {
        $c = new Container();
        $c->add('a', 1);
        $c->wrap('a', function($a) {
            return $a + 1;
        });
        $c->wrap('a', function($a, $c) {
            return $a * $c->get('b');
        });
        $c->add('b', 2);
        expect($c->get('a'))->equal(4);
    });
    it('caches services', function() {
        $c = new Container();
        $c->add('a', function() { return new stdClass(); });
        expect($c->get('a'))->equal($c->get('a'));
    });
    it('does not freeze values', function() {
        $c = new Container();
        $c->add('a', 1);
        $c->get('a');
        $c->add('a', 2);
        expect($c->get('a'))->equal(2);
    });
});
