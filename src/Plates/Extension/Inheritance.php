<?php

namespace Plates\Extension;

class Inheritance extends Base
{
    public $methods = ['startBlock', 'endBlock'];
    public $engine;
    public $template;
    public $blocks;

    public function __construct()
    {
        $this->blocks = [];
    }

    public function startBlock($name)
    {
        $this->blocks[] = $name;
        ob_start();
    }

    public function endBlock()
    {
        if (!count($this->blocks)) {
            trigger_error('You must open a block before you can end it.', E_USER_ERROR);
        }

        $output = ob_get_contents();

        ob_end_clean();

        $this->template->{end($this->blocks)} = $output;

        array_pop($this->blocks);
    }
}
