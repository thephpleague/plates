<?php

namespace League\Plates\Extension;

/**
 * Extension that adds the ability to nest templates into other templates.
 */
class Nest implements ExtensionInterface
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
            'get' => 'getRenderedTemplate',
            'insert' => 'insertRenderedTemplate'
        );
    }

    /**
     * Get a rendered template.
     * @param string $name
     * @param array $data
     * @return string
     */
    public function getRenderedTemplate($name, Array $data = null)
    {
        return $this->engine->makeTemplate()->render($name, $data);
    }

    /**
     * Output a rendered template.
     * @param string $name
     * @param array $data
     * @return null
     */
    public function insertRenderedTemplate($name, Array $data = null)
    {
        echo $this->getRenderedTemplate($name, $data);
    }
}
