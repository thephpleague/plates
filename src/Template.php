<?php

namespace League\Plates;

/**
 * Container which holds template variables and provides access to template functions.
 */
class Template
{
    /**
     * The name of the template.
     * @var string
     */
    protected $name;

    /**
     * Instance of the template engine.
     * @var Engine
     */
    protected $engine;

    /**
     * The variables assigned to the template.
     * @var array
     */
    protected $variables = array();

    /**
     * An array of section content.
     * @var array
     */
    protected $sections = array();

    /**
     * The name of the template's layout.
     * @var string
     */
    protected $layoutName;

    /**
     * The variables assigned to the template's layout.
     * @var array
     */
    protected $layoutData;

    /**
     * Whether or not the template is currently rendering.
     * @var bool
     */
    protected $rendering = false;

    /**
     * Create new Template instance.
     * @param Engine $engine
     * @param string $name
     */
    public function __construct(Engine $engine, $name)
    {
        $this->engine = $engine;
        $this->name = $name;
    }

    /**
     * Magic method used to call extension functions.
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!$this->rendering) {
            throw new \LogicException('Calling functions is only possible within templates.');
        }

        return $this->engine->getFunction($name)->call($this, $arguments);
    }

    /**
     * Magic method used to set template variables.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        throw new \LogicException('Variable assignment to template object is not permitted.');
    }

    /**
     * Assign variables to template object.
     * @param  array $data
     * @return null
     */
    public function data(array $data = array())
    {
        if ($this->rendering) {
            throw new \LogicException('Calling the data() method is not possible within templates.');
        }

        $this->variables = array_merge($this->variables, $data);
    }

    /**
     * Render the template and layout.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render(array $data = array())
    {
        // Check if this template is already being rendered. If it is, that means this
        // method has been called from within a template, which will not work. In this
        // case, throw an exception.
        if ($this->rendering) {
            throw new \LogicException('Calling the render() method is not possible within templates.');
        }

        // Assign both shared data and the data passed to this method to the template.
        // Data passed to this method is assigned second, meaning it takes priority
        // over shared template data in the event of a conflict.
        $this->data($this->engine->getSharedData($this->name));
        $this->data($data);

        // Once the data passed to this method has been assigned to the template, we must
        // clear the variable from the local scope. This is done before we extract the
        // template variables, allowing "data" to be used as a template variable.
        unset($data);

        // Extract the templates variables so that they are available as locally scoped
        // variables within the template.
        extract($this->variables);

        // With the template variables in place, rendering can begin. We start by setting
        // the rendering boolean to true. This is a flag used to verify template method
        // calls. The rendering flag must be set after assigning the data, as the
        // data() method isn't available while rendering.
        $this->rendering = true;

        // Start the output buffering to "catch" all content outputted in the template.
        ob_start();

        // Include the template file. If the optional compiler is enabled, the engine
        // will automatically compile the template and return the cached template
        // path. Otherwise, it will simply return the original template path.
        include($this->engine->getTemplateRenderPath($this->name));

        // Stop the output buffering and put all the outputted content in a variable.
        $content = ob_get_clean();

        // Check to see if a layout was set during the execution of the template. If yes,
        // create a new template and assign the layout data and sections. A reserved
        // section named "content" will be set with the content from the previously
        // rendered template.
        if (isset($this->layoutName)) {
            $layout = new Template($this->engine, $this->layoutName);
            $layout->sections = array_merge($this->sections, array('content' => $content));
            $content = $layout->render($this->layoutData);
        }

        // Rendering is complete, set the rendering flag back to false.
        $this->rendering = false;

        return $content;
    }

    /**
     * Set the template's layout.
     * @param  string $name
     * @param  array  $data
     * @return null
     */
    protected function layout($name, array $data = array())
    {
        $this->layoutName = $name;
        $this->layoutData = $data;
    }

    /**
     * Start a new section block.
     * @param  string $name
     * @return null
     */
    protected function start($name)
    {
        if ($name === 'content') {
            throw new \LogicException('The section name "content" is reserved.');
        }

        $this->sections[$name] = '';

        ob_start();
    }

    /**
     * Stop the current section block.
     * @return null
     */
    protected function stop()
    {
        if (empty($this->sections)) {
            throw new \LogicException('You must start a section before you can stop it.');
        }

        end($this->sections);

        $this->sections[key($this->sections)] = ob_get_clean();
    }

    /**
     * Returns the content for a section block.
     * @param  string $name
     * @return null
     */
    protected function section($name)
    {
        if (!isset($this->sections[$name])) {
            return null;
        }

        return $this->sections[$name];
    }
}
