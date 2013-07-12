<?php

namespace Plates\Extension;

class Escape extends Base
{
    public $methods = array('escape', 'e');
    public $engine;
    public $template;

    public function escape($var)
    {
        return htmlentities($var);
    }

    public function e($var)
    {
        return $this->escape($var);
    }
}
