<?php

namespace Plates\Extension;

class Batch implements ExtensionInterface
{
    public $engine;
    public $template;

    public function getMethods()
    {
        return array(
            'batch' => 'runBatch'
        );
    }

    public function runBatch($var, $methods)
    {
        foreach (explode('|', $methods) as $method) {
            if ($this->engine->functionExists($method)) {
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
