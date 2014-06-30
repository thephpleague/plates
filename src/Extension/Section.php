<?php

namespace League\Plates\Extension;

/**
 * Extension that adds the ability to nest templates into other templates.
 */
class Section implements ExtensionInterface
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
     * The stack of layout sections.
     * @var array
     */
    public $sections = array();

    /**
     * Get the defined extension functions.
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'start' => 'startSection',
            'end' => 'endSection',
            'section' => 'getSection'
        );
    }

    /**
     * Start a new section block.
     * @param  string $name
     * @return null
     */
    public function startSection($name)
    {
        $this->sections[$name] = '';

        ob_start();
    }

    /**
     * End the current section block.
     * @return null
     */
    public function endSection()
    {
        if (empty($this->sections)) {
            throw new \LogicException('You must start a section before you can end it.');
        }

        end($this->sections);

        $this->sections[key($this->sections)] = ob_get_clean();
    }

    /**
     * Returns the content for a section block.
     * @param  string $name
     * @return null
     */
    public function getSection($name)
    {
        if (!isset($this->sections[$name])) {
            return null;
        }

        return $this->sections[$name];
    }
}
