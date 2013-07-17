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
            if ($this->engine->methodExists($method)) {
                $var = $this->template->$method($var);
            } else if (is_callable($method)) {
                $var = call_user_func($method, $var);
            } else {
                throw new \LogicException('The batch method was unable to find the supplied "' . $method . '" function.');
            }
        }

        return $var;
    }
}
