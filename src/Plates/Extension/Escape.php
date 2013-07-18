<?php

namespace Plates\Extension;

class Escape
{
    public $methods = array('escape', 'e');
    public $engine;
    public $template;

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function e($string)
    {
        return $this->escape($string);
    }
}
