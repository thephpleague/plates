<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

class PlatesRenderTemplate implements Plates\RenderTemplate
{
    public function renderTemplate(Plates\Template $template) {
        $context = $template->getContext();
        if (isset($context['layout'])) {
            return $this->renderTemplate($context['layout']);
        }

        return (string) $template->getContent();
    }
}
