<?php

namespace Plates\Extension;

class Escape extends Base
{
    public $methods = ['escape', 'e'];
    public $engine;
    public $template;

    public function escape($str)
    {
        echo htmlentities($str);
    }

    public function e($str)
    {
        $this->escape($str);
    }
}
