<?php

namespace League\Plates\RenderTemplate;

use League\Plates;
use Throwable;
use Exception;

final class StaticFileRenderTemplate implements Plates\RenderTemplate
{
    private $get_contents;

    public function __construct($get_contents = 'file_get_contents') {
        $this->get_contents = $get_contents;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        $path = $template->get('path');
        return ($this->get_contents)($path);
    }
}
