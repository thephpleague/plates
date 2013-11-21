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

        $this->_internal['layout'] = $name;
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

    public function child()
    {
        return $this->_internal['child'];
    }

    public function insert($name, Array $data = null)
    {
        $this->data($data);

        include $this->_internal['engine']->resolvePath($name);
    }

    public function render($name, Array $data = null)
    {
        $this->data($data);

        ob_start();

        include($this->_internal['engine']->resolvePath($name));

        if (isset($this->_internal['layout'])) {

            $this->_internal['child'] = ob_get_contents();

            ob_clean();

            include($this->_internal['engine']->resolvePath($this->_internal['layout']));
        }

        return ob_get_clean();
    }
}
