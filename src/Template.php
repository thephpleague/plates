<?php

namespace League\Plates;

/** Template value object */
final class Template
{
    public $name;
    public $data;
    public $context;

    public function __construct(
        $name,
        array $data = [],
        array $context = []
    ) {
        $this->name = $name;
        $this->data = $data;
        $this->context = $context;
    }

    public function addData(array $data) {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
    public function addContext(array $context) {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
    public function getContextItem($key, $default = null) {
        return array_key_exists($key, $this->context)
            ? $this->context[$key]
            : $default;
    }

    public function resolveName(callable $resolve_name) {
        return $resolve_name($this->name, $this->context);
    }

    public function resolveData(callable $resolve_data) {
        return $resolve_data($this->data, $this->context);
    }

    /** Create a new template based off of this current one */
    public function fork($name, array $data = [], array $context = []) {
        return new self(
            $name,
            array_merge($this->data, $data),
            array_merge($this->context, $context)
        );
    }
}
