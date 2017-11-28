<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

class LayoutRenderTemplate implements Plates\RenderTemplate
{
    private $render;

    public function __construct(RenderTemplate $render) {
        $this->render = $render;
    }

    public function renderTemplate(Plates\Template $template) {
        $content = $this->render->renderTemplate($template);

        $layout = Plates\Template\getLayout($template);
        if (!$layout) {
            return $content;
        }

        Plates\Template\getSections($layout)->add('content', $content);

        return $this->renderTemplate($layout);
    }
}
