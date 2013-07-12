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
            trigger_error('Unable to render correctly, there are incomplete nested templates.', E_USER_ERROR);
        }

        return $output;
    }

    public function __call($name, $arguments)
    {
        $extension = $this->engine->getExtension($name);
        $extension->engine = $this->engine;
        $extension->template = $this;

        return call_user_func_array([$extension, $name], $arguments);
    }
}
