<?php

namespace League\Plates;

class Template
{
    private $_internal = array();

    public function __construct(Engine $engine)
    {
        $this->_internal['engine'] = $engine;
    }

    public function __call($name, $arguments)
    {
        $function = $this->_internal['engine']->getFunction($name);
        $function[0]->template = $this;

        return call_user_func_array($function, $arguments);
    }

    public function data(Array $data = null)
    {
        if (!is_null($data)) {
            foreach ($data as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    public function layout($name, Array $data = null)
    {
        $this->data($data);

        $this->_internal['layout_name'] = $name;
    }

    public function start($name)
    {
        $this->_internal['sections'][] = $name;

        ob_start();
    }

    public function end()
    {
        if (!isset($this->_internal['sections']) or !count($this->_internal['sections'])) {
            throw new \LogicException('You must start a section before you can end it.');
        }

        $this->{end($this->_internal['sections'])} = ob_get_clean();

        array_pop($this->_internal['sections']);
    }

    public function content()
    {
        if (!isset($this->_internal['layout_content'])) {
            throw new \LogicException('No layout content found.');
        }

        return $this->_internal['layout_content'];
    }

    public function child()
    {
        return $this->content();
    }

    public function insert($name, Array $data = null)
    {
        echo $this->_internal['engine']->makeTemplate()->render($name, $data);
    }

    public function render($name, Array $data = null)
    {
        ob_start();

        $this->data($data);

        include($this->_internal['engine']->resolvePath($name));

        while (isset($this->_internal['layout_name'])) {

            $this->_internal['layout_content'] = ob_get_contents();
            $this->_internal['layout_path'] = $this->_internal['engine']->resolvePath($this->_internal['layout_name']);
            $this->_internal['layout_name'] = null;

            ob_clean();

            include($this->_internal['layout_path']);
        }

        return ob_get_clean();
    }
}
