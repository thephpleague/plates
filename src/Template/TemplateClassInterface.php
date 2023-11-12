<?php

namespace League\Plates\Template;

interface TemplateClassInterface
{
    public function display(Template $tpl): void;
}
