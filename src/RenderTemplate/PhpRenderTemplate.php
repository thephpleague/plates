<?php

namespace League\Plates\RenderTemplate;

use League\Plates;

final class PhpRenderTemplate implements Plates\RenderTemplate
{
    private $bind;

    public function __construct(callable $bind = null) {
        $this->bind = $bind;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $render = null) {
        $inc = self::createInclude();
        $inc = $this->bind ? ($this->bind)($inc, $template) : $inc;

        return Plates\Util\obWrap(function() use ($inc, $template) {
            $inc($template->get('path'), $template->data);
        });
    }

    private static function createInclude() {
        return function() {
            extract(func_get_arg(1));
            include func_get_arg(0);
        };
    }
}
