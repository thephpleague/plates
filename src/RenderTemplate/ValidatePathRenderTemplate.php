<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class ValidatePathRenderTemplate extends RenderTemplateDecorator
{
    private $file_exists;

    public function __construct(Plates\RenderTemplate $render, $file_exists = 'file_exists') {
        parent::__construct($render);
        $this->file_exists = $file_exists;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        $path = $template->get('path');
        if (!$path || ($this->file_exists)($path)) {
            return $this->render->renderTemplate($template, $rt ?: null);
        }

        throw new Plates\Exception\RenderTemplateException('Template path ' . $path . ' is not a valid path for template: ' . $template->name);
    }
}
