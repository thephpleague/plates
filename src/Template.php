<?php

namespace League\Plates;

/**
 * Container which holds template variables and provides access to template functions.
 */
class Template
{
    /**
     * The variables available on the template.
     * @var array
     */
    protected $variables = array();

    /**
     * The view engine.
     * @var Engine
     */
    protected $engine;

    /**
     * The name of the layout.
     * @var string
     */
    protected $layoutName;

    /**
     * The contents of the layout.
     * @var string
     */
    protected $layoutContent;

    /**
     * The path to the layout.
     * @var string
     */
    protected $layoutPath;

    /**
     * The stack of layout blocks.
     * @var array
     */
    protected $sections = array();

    /**
     * Whether or not the template is currently rendering.
     * @var bool
     */
    protected $rendering = false;

    /**
     * Create new Template instance.
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Magic method used to call extension functions.
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $function = $this->engine->getFunction($name);
        $function[0]->template = $this;

        return call_user_func_array($function, $arguments);
    }

    /**
     * Magic method used to get template variables.
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (!isset($this->variables[$key])) {
            throw new \OutOfBoundsException('Variable not set on template: '.$key);
        }

        return $this->variables[$key];
    }

    /**
     * Magic method used to set template variables.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * Magic method to allow isset() on template variables.
     * @param  string  $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->variables[$key]);
    }

    /**
     * Magic method to allow unset() on template variables.
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->variables[$key]);
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
                $this->variables[$name] = $value;
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

        $this->layoutName = $name;
    }

    /**
     * Get the template content from within a layout.
     * @return string
     */
    public function content()
    {
        if (!isset($this->layoutContent)) {
            throw new \LogicException('Content is only available in layout templates.');
        }

        return $this->layoutContent;
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
        $this->sections[] = $name;

        ob_start();
    }

    /**
     * End the current section block.
     * @return null
     */
    public function end()
    {
        if (!isset($this->sections) or !count($this->sections)) {
            throw new \LogicException('You must start a section before you can end it.');
        }

        $this->{end($this->sections)} = ob_get_clean();

        array_pop($this->sections);
    }

    /**
     * Render the template and any layouts.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, Array $data = null)
    {
        if ($this->rendering) {
            throw new \LogicException('You cannot render a template from within a template.');
        }

        ob_start();

        $this->rendering = true;

        $this->data($data);

        include($this->engine->resolvePath($name));

        while (isset($this->layoutName)) {

            $this->layoutContent = ob_get_contents();
            $this->layoutPath = $this->engine->resolvePath($this->layoutName);
            $this->layoutName = null;

            ob_clean();

            include($this->layoutPath);
        }

        $this->rendering = false;

        return ob_get_clean();
    }

    public function getEngine()
    {
        return $this->engine;
    }
}
