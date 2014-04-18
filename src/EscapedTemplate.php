<?php

namespace League\Plates;

/**
 * Extension of template that returns escaped data by default.
 */
class EscapedTemplate extends Template
{

    /**
     * Protected data storage.
     * @var array
     */
    protected $_data = array();

    /**
     * Magic object get. Returns an escaped value from protected storage.
     * @param string $key
     * @return string
     */
    public function __get($key)
    {
        $value = $this->raw($key);
        return $this->escape($value);
    }
    
    /**
     * Magic object set. Adds values to protected storage.
     * @param string $key
     * @return void
     */
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }
    
    /**
     * Magic object isset. Checks if values are in protected storage.
     * @param string $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
    
    /**
     * Get raw stored value.
     * @param string $key
     * @return mixed
     */
    public function raw($key)
    {
        return $this->_data[$key];
    }
    
    /**
     * Make a string HTML safe by escaping HTML characters.
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 8);
}
