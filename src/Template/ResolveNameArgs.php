<?php

namespace League\Plates\Template;

class ResolveNameArgs
{
    public $name;
    public $context;
    private $resolve_name;

    public function __construct($name, array $context, callable $resolve_name) {
        $this->name = $name;
        $this->context = $context;
        $this->resolve_name = $resolve_name;
    }

    public function withName($name) {
        return new self($name, $this->context, $this->resolve_name);
    }

    public function withContext(array $context) {
        return new self($this->name, $context, $this->resolve_name);
    }

    public function resolveName($name = null, array $context = []) {
        return new self($name ?: $this->name, $context ?: $this->context, $this->resolve_name);
    }
}
