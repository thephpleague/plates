<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

class PlatesRenderTemplate implements Plates\RenderTemplate
{
    public function renderTemplate(Plates\Template $template) {
        if (isset($template->context['layout'])) {
            return $this->renderTemplate($template->context['layout']);
        }

        return (string) $template->content;
    }
}
