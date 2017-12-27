<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class MockRenderTemplate implements Plates\RenderTemplate
{
    private $mocks;

    public function __construct(array $mocks) {
        $this->mocks = $mocks;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        if (!isset($this->mocks[$template->name])) {
            throw new Plates\Exception\RenderTemplateException('Mock include does not exist for name: ' . $template->name);
        }

        return $this->mocks[$template->name]($template);
    }
}
