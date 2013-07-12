<?php

namespace Plates;

class Template
{
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render($path)
    {
        ob_start();

        include($this->engine->resolvePath($path));

        $output = ob_get_contents();

        ob_end_clean();

        if (ob_get_level()) {
            throw new \LogicException('Unable to render correctly, there are incomplete nested templates.');
        }

        return $output;
    }

    public function __call($name, $arguments)
    {
        $extension = $this->engine->getExtension($name);
        $extension->engine = $this->engine;
        $extension->template = $this;

        return call_user_func_array(array($extension, $name), $arguments);
    }
}
