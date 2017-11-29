<?php

namespace League\Plates\RenderContext;

use League\Plates;

final class FuncArgs
{
    public $render;
    public $template;
    public $func_name;
    public $args;

    public function __construct(Plates\RenderTemplate $render, Plates\Template $template, $func_name, $args = []) {
        $this->render = $render;
        $this->template = $template;
        $this->func_name = $func_name;
        $this->args = $args;
    }

    public function withName($func_name) {
        return new self($this->render, $this->template, $func_name, $this->args);
    }
    public function withArgs($args) {
        return new self($this->render, $this->template, $this->func_name, $args);
    }
}
