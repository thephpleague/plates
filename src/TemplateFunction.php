<?php

namespace League\Plates;

/**
 * Used to store information about a template function.
 */
class TemplateFunction
{
    /**
     * The template function's name.
     * @var string
     */
    private $name;

    /**
     * The template function's callback.
     * @var callable
     */
    private $callback;

    /**
     * Indicates if the template function should be automatically escaped.
     * @var boolean
     */
    private $raw;

    /**
     * Create new Template instance.
     * @param  string   $name
     * @param  callable $callback
     * @param  boolean  $raw
     */
    public function __construct($name, $callback, $raw = false)
    {
        if (!is_string($name) or empty($name)) {
            throw new \LogicException(
                'Not a valid function name.'
            );
        }

        if (!is_callable($callback)) {
            throw new \LogicException(
                'Not a valid function callback.'
            );
        }

        if (!is_bool($raw)) {
            throw new \LogicException(
                'Not a valid function boolean.'
            );
        }

        $this->name = $name;
        $this->callback = $callback;
        $this->raw = $raw;
    }

    /**
     * Call the template function.
     * @param  Template  $template
     * @param  array     $arguments
     * @return mixed
     */
    public function call(Template $template = null, $arguments = array())
    {
        if (is_array($this->callback) and
            isset($this->callback[0]) and
            $this->callback[0] instanceof \League\Plates\Extension\ExtensionInterface
        ) {
            $this->callback[0]->template = $template;
        }

        return call_user_func_array($this->callback, $arguments);
    }

    /**
     * Get the template function's name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the template function's callback.
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Check if the template function should be automatically escaped.
     * @return boolean
     */
    public function isRaw()
    {
        return $this->raw;
    }
}
