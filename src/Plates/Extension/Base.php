<?php

namespace Plates\Extension;

class Base
{
    public function __get($name)
    {
        return $this->template->$name;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->template, $name], $arguments);
    }
}
