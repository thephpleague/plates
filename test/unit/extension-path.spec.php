<?php

use League\Plates\Extension\Path\ResolvePathArgs;
use League\Plates\Template;
use function League\Plates\{
    Extension\Path\idResolvePath,
    Extension\Path\relativeResolvePath,
    Extension\Path\prefixResolvePath,
    Extension\Path\extResolvePath,
    Extension\Path\stripPrefixNormalizeName,
    Extension\Path\stripExtNormalizeName,
    Extension\Path\normalizeNameCompose,
    Util\joinPath,
    Util\stack
};

describe('Extension\Path', function() {
    beforeEach(function() {
        $this->args = new ResolvePathArgs('', [], new Template('a'));
    });

    describe('idResolvePath', function() {
        it('returns the name passed in', function() {
            $path = stack([idResolvePath()])($this->args->withPath('foo'));
            expect($path)->equal('foo');
        });
    });
    describe('relativeResolvePath', function() {
        beforeEach(function() {
            $this->args->template = new Template('a', [], [], null, (new Template('b', [], ['path' => 'foo/b.phtml']))->reference);
            $this->resolve = stack([idResolvePath(), relativeResolvePath()]);
        });
        it('resolves the name relative to the current_directory in context', function() {
            $resolve = $this->resolve;
            $path = $resolve($this->args->withPath('./bar'));
            $parent_path = $resolve($this->args->withPath('../bar'));
            expect([$path, $parent_path])->equal([
                joinPath(['foo', './bar']),
                joinPath(['foo', '../bar'])
            ]);
        });
        it('does nothing if the path does not start with a relative path', function() {
            $resolve = $this->resolve;
            expect([
                $resolve($this->args->withPath('.../foo')),
                $resolve($this->args->withPath('/foo')),
                $resolve($this->args->withPath('foo')),
            ])->equal([
                '.../foo',
                '/foo',
                'foo',
            ]);
        });
    });
    describe('prefixResolvePath', function() {
        beforeEach(function() {
            $this->resolve = stack([idResolvePath(), prefixResolvePath(['/foo', '/bar'], function() {
                return true;
            })]);
        });
        it('prefixes non absolute paths with a base path', function() {
            $path = ($this->resolve)($this->args->withPath('bar'));
            expect($path)->equal(joinPath(['/foo', 'bar']));
        });
        it('does nothing if path starts with /', function() {
            $path = ($this->resolve)($this->args->withPath('/acme'));
            expect($path)->equal('/acme');
        });
        xit('applies the prefixes in order until it finds a valid path');
        xit('strips the prefix from absolute paths while finding the proper prefix');
    });
    describe('extResolvePath', function() {
        it('appends an extension to the name', function() {
            $resolve = stack([idResolvePath(), extResolvePath('bar')]);
            $path = $resolve($this->args->withPath('foo'));
            expect($path)->equal('foo.bar');
        });
        it('does not append the name if ext already exists', function() {
            $resolve = stack([idResolvePath(), extResolvePath('bar')]);
            $path = $resolve($this->args->withPath('foo.bar'));
            expect($path)->equal('foo.bar');
        });
    });
    describe('normalizeNameCompose', function() {
        it('normalizes the name field into the normalized_name template attribute if it is a path', function() {
            $compose = normalizeNameCompose(function($name) {
                return $name . 'bar';
            });
            expect($compose((new Template('/foo', [], ['path' => '/foo'])))->get('normalized_name'))->equal('/foobar');
        });
        it('sets the name into the normalized_name attribute if it is not a path', function() {
            $compose = normalizeNameCompose(function($name) {
                return $name . 'bar';
            });
            expect($compose((new Template('foo', [], ['path' => 'foo'])))->get('normalized_name'))->equal('foo');
        });
    });
    describe('stripExtNormalizeName', function() {
        it('strips the extension if it exists', function() {
            $nn = stripExtNormalizeName();
            expect($nn('abc.ext'))->equal('abc');
        });
        it('does nothing if no extension found', function() {
            $nn = stripExtNormalizeName();
            expect($nn('abc'))->equal('abc');
        });
    });
    describe('stripPrefixNormalizeName', function() {
        it('strips a prefix if there is a match', function() {
            $nn = stripPrefixNormalizeName([
                '/a/b/c',
                '/b',
            ]);
            expect($nn('/a/b/c/d'))->equal('d');
            expect($nn('/b/d/c'))->equal('d/c');
        });
        it('filters any empty prefixes before matching', function() {
            $nn = stripPrefixNormalizeName([
                '',
                '/b',
            ]);
            expect($nn('/a'))->equal('/a');
        });
    });
});
