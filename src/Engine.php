<?php

namespace League\Plates;

/** API for the Plates system */
class Engine
{
    private $render;
    private $context;

    public function __construct(RenderTemplate $render = null) {
        $this->render = $render ?: new RenderTemplate\PlatesRenderTemplate();
        $this->context = [];
    }

    /** @return string */
    public function render($template_name, array $data) {
        $template = new Template($template_name, $data, [
            'engine' => $this,
        ]);
        return $this->render->renderTemplate($template);
    }

    public function addContext(array $context) {
        $this->context = array_merge($this->context, $context);
    }

    public function getContext() {
        return $this->context;
    }
}
