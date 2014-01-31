<?php

namespace League\Plates\Extension;

class URI implements ExtensionInterface
{
    public $engine;
    public $template;
    public $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->parts = explode('/', $this->uri);
    }

    public function getFunctions()
    {
        return array(
            'uri' => 'runUri'
        );
    }

    public function runUri($var1 = null, $var2 = null, $var3 = null, $var4 = null)
    {
        if (is_null($var1)) {
            return $this->uri;
        }

        if (is_numeric($var1) and is_null($var2) and is_null($var3) and is_null($var4)) {
            return $this->parts[$var1];
        }

        if (is_numeric($var1) and is_string($var2) and is_null($var3) and is_null($var4)) {
            return $this->parts[$var1] === $var2;
        }

        if (is_numeric($var1) and is_string($var2) and is_string($var3) and is_null($var4)) {
            if ($this->parts[$var1] === $var2) {
                return $var3;
            } else {
                return false;
            }
        }

        if (is_numeric($var1) and is_string($var2) and is_string($var3) and is_string($var4)) {
            if ($this->parts[$var1] === $var2) {
                return $var3;
            } else {
                return $var4;
            }
        }

        if (is_string($var1) and is_null($var2) and is_null($var3) and is_null($var4)) {
            return preg_match('#^' . $var1 . '$#', $this->uri) === 1;
        }

        if (is_string($var1) and is_string($var2) and is_null($var3) and is_null($var4)) {
            if (preg_match('#^' . $var1 . '$#', $this->uri) === 1) {
                return $var2;
            } else {
                return false;
            }
        }

        if (is_string($var1) and is_string($var2) and is_string($var3) and is_null($var4)) {
            if (preg_match('#^' . $var1 . '$#', $this->uri) === 1) {
                return $var2;
            } else {
                return $var3;
            }
        }

        throw new \LogicException('Invalid use of the uri() method.');
    }
}
