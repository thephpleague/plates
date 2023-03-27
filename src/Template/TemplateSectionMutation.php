<?php

namespace League\Plates\Template;

/**
 * Defines the object format of a template mutation
 * across template and nested templates to be rendered
 * as final Template's Section.
 */
class TemplateSectionMutation
{
    public $mode;

    public $content;

    public function __construct($mode, $content)
    {
        $this->mode = $mode;
        $this->content = $content;
    }
}