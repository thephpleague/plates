<?php

use League\Plates\Extension\AutoEscape\{EscapedProxy};

function joinProxiedArray($data) {
    return implode("", iterator_to_array($data));
}

describe('Extension\AutoEscape', function() {
    beforeEach(function() {
        $this->escape = function($v) {
            return '_' . $v;
        };
    });
    it('can escape strings', function() {
        $v = EscapedProxy::create('a', $this->escape);
        expect((string) $v)->equal('_a');
    });
    it('ignores non strings, objects, and arrays', function() {
        expect(EscapedProxy::create(0, $this->escape))->equal(0);
        expect(EscapedProxy::create(0.0, $this->escape))->equal(0.0);
        expect(EscapedProxy::create(null, $this->escape))->equal(null);
    });
    it('can wrap object properties', function() {
        $obj = new stdClass();
        $obj->a = 1;
        $obj->b = "b";
        $wrapped = EscapedProxy::create($obj, $this->escape);
        expect($wrapped->a)->equal(1);
        expect((string) $wrapped->b)->equal("_b");
    });
    it('can recursively wrap object properties', function() {
        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj2->a = "a";
        $obj1->a = $obj2;
        $wrapped = EscapedProxy::create($obj1, $this->escape);
        expect((string) $wrapped->a->a)->equal("_a");
    });
    it('can wrap arrays', function() {
        $wrapped = EscapedProxy::create([
            'a' => 1,
            'b' => 'b',
        ], $this->escape);
        expect($wrapped['a'])->equal(1);
        expect((string) $wrapped['b'])->equal("_b");
    });
    it('can wrap iterables', function() {
        $wrapped = EscapedProxy::create(["a", "b"], $this->escape);
        expect(joinProxiedArray($wrapped))->equal("_a_b");
    });
    it('can wrap nested structures', function() {
        $wrapped = EscapedProxy::create([
            'users' => [
                (object) ['id' => 1, 'name' => 'foo', 'pets' => ['a', 'b']],
                (object) ['id' => 2, 'name' => 'bar', 'pets' => ['c', 'd']],
            ]
        ], $this->escape);

        expect($wrapped['users'][0]->id)->equal(1);
        expect((string) $wrapped['users'][0]->name)->equal('_foo');
        expect(joinProxiedArray($wrapped['users'][0]->pets))->equal('_a_b');
        expect($wrapped['users'][1]->id)->equal(2);
        expect((string) $wrapped['users'][1]->name)->equal('_bar');
        expect(joinProxiedArray($wrapped['users'][1]->pets))->equal('_c_d');
    });
    it('can unwrap a proxy', function() {
        $res = ['users' => [
            (object) ['id' => 1, 'name' => 'foo', 'pets' => ['a', 'b']],
            (object) ['id' => 2, 'name' => 'bar', 'pets' => ['c', 'd']],
        ]];
        $wrapped = EscapedProxy::create($res, $this->escape);

        expect($wrapped->__unwrap())->equal($res);
    });
    it('can compare two similar string proxies', function() {
        expect(EscapedProxy::create("a", $this->escape))->loosely->equal(EscapedProxy::create("a", $this->escape));
        expect(EscapedProxy::create("a", $this->escape))->loosely->not->equal(EscapedProxy::create("b", $this->escape));
    });
    it('can compare two similar array proxies', function() {
        $res = [1,2,3];
        $res1 = [2,3];
        expect(EscapedProxy::create($res, $this->escape))->loosely->equal(EscapedProxy::create($res, $this->escape));
        expect(EscapedProxy::create($res, $this->escape))->loosely->not->equal(EscapedProxy::create($res1, $this->escape));
    });
    it('can compare two similar object proxies', function() {
        $res = (object) ['a' => 1, 'b' => 2];
        $res1 = (object) ['a' => 2];
        expect(EscapedProxy::create($res, $this->escape))->loosely->equal(EscapedProxy::create($res, $this->escape));
        expect(EscapedProxy::create($res, $this->escape))->loosely->not->equal(EscapedProxy::create($res1, $this->escape));
    });
    it('cannot strictly compare two object proxies', function() {
        $res = (object) ['a' => 1, 'b' => 2];
        $res1 = clone $res;
        expect(EscapedProxy::create($res, $this->escape))->loosely->equal(EscapedProxy::create($res1, $this->escape));
    });
});
