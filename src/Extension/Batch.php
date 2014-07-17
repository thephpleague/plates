<?php

namespace League\Plates\Extension;

/**
 * Extension that adds the ability to apply multiple functions to variables at once.
 */
class Batch implements ExtensionInterface
{
    /**
     * Instance of the engine.
     * @var Template
     */
    public $engine;

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
        $engine->registerEscapedFunction('batch', [$this, 'batch']);

        $this->engine = $engine;
    }

    /**
     * Apply multiple functions to variable.
     * @param  mixed  $var
     * @param  string $functions
     * @return mixed
     */
    public function batch($var, $functions)
    {
        foreach (explode('|', $functions) as $function) {
            if (method_exists($this, $function) or $this->engine->doesFunctionExist($function)) {
                $var = call_user_func(array($this, $function), $var);
            } elseif (is_callable($function)) {
                $var = call_user_func($function, $var);
            } else {
                throw new \LogicException('The batch function could not find the "' . $function . '" function.');
            }
        }

        return $var;
    }
}
