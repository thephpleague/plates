<?php

namespace League\Plates\Extension;

class Insert implements ExtensionInterface
{
    public $engine;
    public $template;

    public function getFunctions()
    {
        return array(
            'insert' => 'insertTemplate'
        );
    }

    public function insertTemplate($name, Array $data = null)
    {
        echo $this->engine->makeTemplate()->render($name, $data);
    }
}
