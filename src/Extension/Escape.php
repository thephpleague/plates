<?php

namespace League\Plates\Extension;

/**
 * Extension that adds string escaping for sanitizing unsafe variables.
 */
class Escape implements ExtensionInterface
{
    /**
     * Instance of the parent engine.
     * @var Engine
     */
    public $engine;

    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * Get the defined extension functions.
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'escape' => 'escapeString',
            'e' => 'escapeString'
        );
    }

    /**
     * Escape string.
     * @param string $string
     * @return string
     */
    public function escapeString($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 8);
}
