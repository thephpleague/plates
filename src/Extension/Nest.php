<?php

namespace League\Plates\Extension;

/**
 * Extension that adds the ability to nest templates into other templates.
 */
class Nest implements ExtensionInterface
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
        $engine->registerRawFunction('get', [$this, 'get']);
        $engine->registerRawFunction('insert', [$this, 'insert']);

        $this->engine = $engine;
    }

    /**
     * Get a rendered template.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function get($name, array $data = array())
    {
        return $this->engine->render($name, $data);
    }

    /**
     * Output a rendered template.
     * @param  string $name
     * @param  array  $data
     * @return null
     */
    public function insert($name, array $data = array())
    {
        echo $this->engine->render($name, $data);
    }
}
