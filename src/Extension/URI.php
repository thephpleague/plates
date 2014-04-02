<?php

namespace League\Plates\Extension;

/**
 * Extension that adds a number of URI checks.
 */
class URI implements ExtensionInterface
{
    /**
     * Instance of the parent engine.
     * @var Engine
     */
    public $engine;

    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * The request URI.
     * @var string
     */
    public $uri;

    /**
     * The request URI as an array.
     * @var array
     */
    private $parts;

    /**
     * Create new URI instance.
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->parts = explode('/', $this->uri);
    }

    /**
     * Get the defined extension functions.
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'uri' => 'runUri'
        );
    }

    /**
     * Perform URI check.
     * @param integer|string $var1
     * @param string $var2
     * @param string $var3
     * @param string $var4
     * @return boolean|string
     */
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

        throw new \LogicException('Invalid use of the uri function.');
    }
}
