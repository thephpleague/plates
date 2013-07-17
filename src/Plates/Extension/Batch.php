<?php

namespace Plates\Extension;

class Batch
{
    public $methods = array('batch');
    public $engine;
    public $template;

    public function batch($var, $methods)
    {
        foreach (explode('|', $methods) as $method) {
            $var = $this->template->$method($var);
        }

        return $var;
    }
}
