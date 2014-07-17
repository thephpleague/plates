<?php

namespace League\Plates\Extension;

/**
 * Extension that adds string escaping for sanitizing unsafe variables.
 */
class Escape implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * Register extension functions.
     * @return null
     */
    public function register($engine)
    {
        $engine->registerRawFunction('escape', [$this, 'escape']);
        $engine->registerRawFunction('e', [$this, 'escape']);
    }

    /**
     * Escape string.
     * @param  string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 8);
}
