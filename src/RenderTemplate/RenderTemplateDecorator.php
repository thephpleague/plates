<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

abstract class RenderTemplateDecorator implements Plates\RenderTemplate
{
    protected $render;

    public function __construct(Plates\RenderTemplate $render) {
        $this->render = $render;
    }

    abstract public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null);
}
