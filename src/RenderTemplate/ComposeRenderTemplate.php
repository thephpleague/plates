<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class ComposeRenderTemplate extends RenderTemplateDecorator
{
    private $compose;

    public function __construct(Plates\RenderTemplate $render, callable $compose) {
        parent::__construct($render);
        $this->compose = $compose;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        return $this->render->renderTemplate(($this->compose)($template), $rt ?: $this);
    }
}
