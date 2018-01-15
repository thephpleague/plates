<?php

namespace League\Plates\Extension\AutoEscape;

use ArrayAccess;
use IteratorAggregate;
use League\Plates;

final class ObjectEscapedProxy extends EscapedProxy implements ArrayAccess, IteratorAggregate
{
    protected $initialized = false;

    public function offsetExists($offset) {
        return isset($this->wrapped[$offset]);
    }
    public function offsetGet($offset) {
        return EscapedProxy::create($this->wrapped[$offset], $this->escape);
    }
    public function offsetSet($offset, $value) {
        $this->wrapped[$offset] = EscapedProxy::create($value, $this->escape);
    }
    public function offsetUnset($offset) {
        unset($this->wrapped[$offset]);
    }

    public function getIterator() {
        foreach ($this->wrapped as $key => $value) {
            yield $key => EscapedProxy::create($value, $this->escape);
        }
    }

    public function __call($method, array $args) {
        return EscapedProxy::create($this->wrapped->{$method}(...$args), $this->escape);
    }

    public function __get($name) {
        return EscapedProxy::create($this->wrapped->{$name}, $this->escape);
    }
}
