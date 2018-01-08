<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;

final class DefaultLayoutRenderTemplate extends Plates\RenderTemplate\RenderTemplateDecorator
{
    private $layout_path;

    public function __construct(Plates\RenderTemplate $render, $layout_path) {
        parent::__construct($render);
        $this->layout_path = $layout_path;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        $ref = $template->reference;
        $contents = $this->render->renderTemplate($template, $rt ?: $this);

        if ($ref()->get('layout') || $ref()->get('is_default_layout')) {
            return $contents;
        }

        $layout = $ref()->fork($this->layout_path, [], ['is_default_layout' => true]);
        $ref()->with('layout', $layout->reference);

        return $contents;
    }

    public static function factory($layout_path) {
        return function(Plates\RenderTemplate $rt) use ($layout_path) {
            return new self($rt, $layout_path);
        };
    }
}


