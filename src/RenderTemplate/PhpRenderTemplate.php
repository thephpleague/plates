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

        $cur_level = ob_get_level();
        try {
            return $inc($template->get('path'), $template->data);
        } catch (\Exception $e) {}
          catch (\Throwable $e) {}

        // clean the ob stack
        while (ob_get_level() > $cur_level) {
            ob_end_clean();
        }

        throw $e;
    }

    private static function createInclude() {
        return function() {
            ob_start();
            extract(func_get_arg(1));
            include func_get_arg(0);
            return ob_get_clean();
        };
    }
}
