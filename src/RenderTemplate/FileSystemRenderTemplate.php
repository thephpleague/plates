<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class FileSystemRenderTemplate implements Plates\RenderTemplate
{
    private $render_sets;

    public function __construct(array $render_sets) {
        $this->render_sets = $render_sets;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        foreach ($this->render_sets as list($match, $render)) {
            if ($match($template)) {
                return $render->renderTemplate($template, $rt ?: $this);
            }
        }

        throw new Plates\Exception\RenderTemplateException('No renderer was available for the template: ' . $template->name);
    }
}
