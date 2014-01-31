<?php

namespace League\Plates\Extension;

class Escape implements ExtensionInterface
{
    public $engine;
    public $template;

    public function getFunctions()
    {
        return array(
            'escape' => 'escapeString',
            'e' => 'escapeString'
        );
    }

    public function escapeString($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 8);
}
