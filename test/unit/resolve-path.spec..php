<?php

use League\Plates\Extension\Path\ResolvePathArgs;
use function League\Plates\{
    Extension\Path\idResolveName,
    Extension\Path\relativeResolveName,
    Extension\Path\prefixResolveName,
    Extension\Path\extResolveName,
    Util\joinPath,
    Util\stack
};

describe('resolvePath', function() {
    beforeEach(function() {
        $this->args = new ResolvePathArgs('', [], function() {});
    });

    describe('idResolveName', function() {
        it('returns the name passed in', function() {
            $path = idResolveName()($this->args->withName('foo'));
            expect($path)->equal('foo');
        });
    });
    describe('relativeResolveName', function() {
        beforeEach(function() {
            $this->args = $this->args->withContext(['current_directory' => 'foo']);
            $this->resolve = stack([relativeResolveName(), idResolveName()]);;
        });
        it('resolves the name relative to the current_directory in context', function() {
            $resolve = $this->resolve;
            $path = $resolve($this->args->withName('./bar'));
            $parent_path = $resolve($this->args->withName('../bar'));
            expect([$path, $parent_path])->equal([
                joinPath(['foo', './bar']),
                joinPath(['foo', '../bar'])
            ]);
        });
        it('does nothing if the path does not start with a relative path', function() {
            $resolve = $this->resolve;
            expect([
                $resolve($this->args->withName('.../foo')),
                $resolve($this->args->withName('/foo')),
                $resolve($this->args->withName('foo')),
            ])->equal([
                '.../foo',
                '/foo',
                'foo',
            ]);
        });
    });
    describe('prefixResolveName', function() {
        beforeEach(function() {
            $this->resolve = stack([prefixResolveName('foo'), idResolveName()]);
        });
        it('prefixes non absolute paths with a base path', function() {
            $path = ($this->resolve)($this->args->withName('bar'));
            expect($path)->equal(joinPath(['foo', 'bar']));
        });
        it('does nothing if path starts with /', function() {
            $path = ($this->resolve)($this->args->withName('/bar'));
            expect($path)->equal('/bar');
        });
    });
    describe('extResolveName', function() {
        it('appends an extension to the name', function() {
            $resolve = stack([extResolveName('bar'), idResolveName()]);
            $path = $resolve($this->args->withName('foo'));
            expect($path)->equal('foo.bar');
        });
        it('does not append the name if ext already exists', function() {
            $resolve = stack([extResolveName('bar'), idResolveName()]);
            $path = $resolve($this->args->withName('foo.bar'));
            expect($path)->equal('foo.bar');
        });
    });
});
