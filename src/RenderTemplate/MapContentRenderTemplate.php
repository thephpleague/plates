<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class MapContentRenderTemplate extends RenderTemplateDecorator
{
    private $map_content;

    public function __construct(Plates\RenderTemplate $render, callable $map_content) {
        parent::__construct($render);
        $this->map_content = $map_content;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        return ($this->map_content)($this->render->renderTemplate($template, $rt ?: $this));
    }

    public static function base64Encode(Plates\RenderTemplate $render) {
        return new self($render, 'base64_encode');
    }
}
