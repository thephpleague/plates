<?php

namespace League\Plates\RenderContext;

use League\Plates;

class PropArgs extends AbstractArgs
{
    public $prop_name;

    public function __construct(Plates\RenderTemplate $render, Plates\Template $template, $prop_name) {
        parent::__construct($render, $template);
        $this->prop_name = $prop_name;
    }

    public function getName() {
        return $this->prop_name;
    }
}
