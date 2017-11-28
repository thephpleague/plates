<?php

namespace League\Plates\RenderContext;

use League\Plates;

class FuncArgs extends AbstractArgs
{
    public $func_name;
    public $args;

    public function __construct(Plates\RenderTemplate $render, Plates\Template $template, $func_name, array $args) {
        parent::__construct($render, $template);
        $this->func_name = $func_name;
        $this->args = $args;
    }

    public function isInvoke() {
        return $this->func_name === null;
    }

    public function getName() {
        return $this->func_name;
    }
}
