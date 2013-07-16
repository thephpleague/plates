<?php

namespace Plates\Extension;

class Asset
{
    public $methods = array('asset');
    public $engine;
    public $template;
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function asset($url)
    {
        if (file_exists($this->path . $url) and $last_updated = filemtime($this->path . $url)) {

            $path = pathinfo($url);

            if ($path['dirname'] === '/') {
                $path['dirname'] = '';
            }

            return $path['dirname'] . '/' . $path['filename'] . '.' . $last_updated . '.' . $path['extension'];

        } else {

            return $url;
        }
    }
}
