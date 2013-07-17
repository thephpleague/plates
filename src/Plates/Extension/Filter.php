<?php

namespace Plates\Extension;

class Filter
{
    public $methods = array('escape', 'e', 'upper', 'lower', 'title', 'sentence', 'striptags');
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

    public function upper($var)
    {
        return strtoupper($var);
    }

    public function lower($var)
    {
        return strtolower($var);
    }

    public function title($var)
    {
        return ucwords($var);
    }

    public function sentence($var)
    {
        return ucfirst($var);
    }

    public function striptags($var)
    {
        return strip_tags($var);
    }
}
