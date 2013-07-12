<?php

namespace Plates\Extension;

class Inheritance extends Base
{
    public $methods = array('startBlock', 'endBlock');
    public $engine;
    public $template;
    public $blocks = array();

    public function startBlock($name)
    {
        $this->blocks[] = $name;

        ob_start();
    }

    public function endBlock()
    {
        if (!count($this->blocks)) {
            throw new \LogicException('You must open a block before you can end it.');
        }

        $output = ob_get_contents();

        ob_end_clean();

        $this->template->{end($this->blocks)} = $output;

        array_pop($this->blocks);
    }
}
