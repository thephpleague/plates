<?php

namespace League\Plates;

/** mutable template reference to allow relationships */
final class TemplateReference
{
    public $template;

    public function __invoke() {
        return $this->template;
    }

    public function update(Template $template) {
        $this->template = $template;
        return $this;
    }
}
