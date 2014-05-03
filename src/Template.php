<?php

namespace League\Plates;

/**
 * Container which holds template variables and provides access to template functions.
 */
class Template
{
    /**
     * Reserved for internal purposes.
     * @var string
     */
    protected $internal = array();

    /**
     * Create new Template instance.
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->internal['engine'] = $engine;
    }

    /**
     * Magic method used to call extension functions.
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $function = $this->internal['engine']->getFunction($name);
        $function[0]->template = $this;

        return call_user_func_array($function, $arguments);
    }

    /**
     * Bulk assign variables to template object.
     * @param  array $data
     * @return null
     */
    public function data(Array $data = null)
    {
        if (!is_null($data)) {
            foreach ($data as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * Set the template's layout.
     * @param  string $name
     * @param  array  $data
     * @return null
     */
    public function layout($name, Array $data = null)
    {
        $this->data($data);

        $this->internal['layout_name'] = $name;
    }

    /**
     * Get the template content from within a layout.
     * @return string
     */
    public function content()
    {
        if (!isset($this->internal['layout_content'])) {
            throw new \LogicException('Content is only available in layout templates.');
        }

        return $this->internal['layout_content'];
    }

    /**
     * Alias to the content() method.
     * @return string
     */
    public function child()
    {
        return $this->content();
    }

    /**
     * Start a new section block.
     * @param  string $name
     * @return null
     */
    public function start($name)
    {
        $this->internal['sections'][] = $name;

        ob_start();
    }

    /**
     * End the current section block.
     * @return null
     */
    public function end()
    {
        if (!isset($this->internal['sections']) or !count($this->internal['sections'])) {
            throw new \LogicException('You must start a section before you can end it.');
        }

        $this->{end($this->internal['sections'])} = ob_get_clean();

        array_pop($this->internal['sections']);
    }

    /**
     * Render the template and any layouts.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, Array $data = null)
    {
        if (isset($this->internal['rendering']) and $this->internal['rendering']) {
            throw new \LogicException('You cannot render a template from within a template.');
        }

        ob_start();

        $this->internal['rendering'] = true;

        $this->data($data);

        include($this->internal['engine']->resolvePath($name));

        while (isset($this->internal['layout_name'])) {

            $this->internal['layout_content'] = ob_get_contents();
            $this->internal['layout_path'] = $this->internal['engine']->resolvePath($this->internal['layout_name']);
            $this->internal['layout_name'] = null;

            ob_clean();

            include($this->internal['layout_path']);
        }

        $this->internal['rendering'] = false;

        return ob_get_clean();
    }
}
