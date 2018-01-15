<?php

namespace League\Plates\Extension\AutoEscape;

abstract class EscapedProxy
{
    protected $escape;
    protected $wrapped;

    protected function __construct($wrapped, callable $escape) {
        $this->wrapped = $wrapped;
        $this->escape = $escape;
    }

    public function __unwrap() {
        return $this->wrapped;
    }

    public function __toString() {
        return ($this->escape)($this->wrapped);
    }

    public static function create($wrapped, callable $escape) {
        if (is_object($wrapped) || is_array($wrapped)) {
            return new ObjectEscapedProxy($wrapped, $escape);
        }
        if (is_string($wrapped)) {
            return new ScalarEscapedProxy($wrapped, $escape);
        }

        return $wrapped;
    }
}
