<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;

final class LayoutRenderTemplate extends Plates\RenderTemplate\RenderTemplateDecorator
{
    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        $ref = $template->reference;
        $content = $this->render->renderTemplate($template, $rt ?: $this);

        $layout_ref = $ref()->get('layout');
        if (!$layout_ref) {
            return $content;
        }

        $layout = $layout_ref()->with('sections', $ref()->get('sections'));
        $layout->get('sections')->add('content', $content);

        return ($rt ?: $this)->renderTemplate($layout);
    }

    public static function factory() {
        return function(Plates\RenderTemplate $render) {
            return new static($render);
        };
    }
}


