<?php

namespace League\Plates;

class TemplateContext
{
    private $template;

    public function __construct(Template $template) {
        $this->template = $template;
    }

    public function layout($template_name, $data) {
        $layout = new Template($template_name, $data, [
            'children' => $this->template,
        ]);
        $this->template->addContext([
            'layout' => $layout
        ]);
    }

    public function insert($template_name, $data) {
        $child = new Template($template_name, $data);

    }
}
