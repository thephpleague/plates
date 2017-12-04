<?php

namespace League\Plates\Template;

class ResolveDataArgs
{
    public $data;
    public $context;
    private $resolve_data;

    public function __construct(array $data, array $context, $resolve_data) {
        $this->data = $data;
        $this->context = $context;
        $this->resolve_data = $resolve_data;
    }

    public function withData($data) {
        return new self($data, $this->context, $this->resolve_name);
    }

    public function withContext(array $context) {
        return new self($this->data, $context, $this->resolve_name);
    }

    public function resolveData($data = null, array $context = []) {
        return new self($data ?: $this->data, $context ?: $this->context, $this->resolve_data);
    }
}
