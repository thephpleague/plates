<?php

namespace League\Plates\Extension\RenderContext;

use League\Plates;

final class FuncArgs
{
    public $render;
    public $ref;
    public $func_name;
    public $args;

    public function __construct(Plates\RenderTemplate $render, Plates\TemplateReference $ref, $func_name, $args = []) {
        $this->render = $render;
        $this->ref = $ref;
        $this->func_name = $func_name;
        $this->args = $args;
    }

    public function template() {
        return $this->ref->template;
    }

    public function withName($func_name) {
        return new self($this->render, $this->ref, $func_name, $this->args);
    }
    public function withArgs($args) {
        return new self($this->render, $this->ref, $this->func_name, $args);
    }
}
