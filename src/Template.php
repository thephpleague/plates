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
        $this->internal['rendering'] = false;
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
    public function data(array $data = null)
    {
        if (!is_null($data)) {
            foreach ($data as $name => $value) {

                if ($name === 'internal') {
                    throw new \LogicException('Invalid template variable: "internal" is a reserved variable.');
                }

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
    public function layout($name, array $data = null)
    {
        $this->data($data);

        $this->internal['layoutName'] = $name;
    }

    /**
     * Get the template content from within a layout.
     * @return string
     */
    public function content()
    {
        if (!isset($this->internal['layoutContent'])) {
            throw new \LogicException('Content is only available in layout templates.');
        }

        return $this->internal['layoutContent'];
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
     * Render the template and any layouts.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, array $data = null)
    {
        if ($this->internal['rendering'] !== false) {
            throw new \LogicException('You cannot render a template from within a template.');
        }

        ob_start();

        $this->internal['rendering'] = $name;

        unset($name);

        $this->data($data);

        unset($data);

        extract(get_object_vars($this));

        unset($internal);

        include($this->internal['engine']->resolvePath($this->internal['rendering']));

        while (isset($this->internal['layoutName'])) {

            $this->internal['layoutContent'] = ob_get_contents();
            $this->internal['layoutPath'] = $this->internal['engine']->resolvePath($this->internal['layoutName']);
            $this->internal['layoutName'] = null;

            ob_clean();

            extract(get_object_vars($this));

            unset($internal);

            include($this->internal['layoutPath']);
        }

        $this->internal['rendering'] = false;

        return ob_get_clean();
    }
}
