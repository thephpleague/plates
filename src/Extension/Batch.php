<?php

namespace League\Plates\Extension;

/**
 * Extension that adds the ability to apply multiple functions to variables at once.
 */
class Batch implements ExtensionInterface
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
            'batch' => 'runBatch'
        );
    }


    /**
     * Apply multiple functions to variable.
     * @param mixed $var
     * @param string $functions
     * @return mixed
     */
    public function runBatch($var, $functions)
    {
        foreach (explode('|', $functions) as $function) {
            if ($this->engine->functionExists($function)) {
                $var = $this->template->$function($var);
            } else if (is_callable($function)) {
                $var = call_user_func($function, $var);
            } else {
                throw new \LogicException('The batch function could not find the "' . $function . '" function.');
            }
        }

        return $var;
    }
}
