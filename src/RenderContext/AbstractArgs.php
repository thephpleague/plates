<?php

namespace League\Plates\RenderContext;

use League\Plates;

abstract class AbstractArgs
{
    public $render;
    public $template;

    public function __construct(Plates\RenderTemplate $render, Plates\Template $template) {
        $this->render = $render;
        $this->template = $template;
    }

    abstract public function getName();
}
