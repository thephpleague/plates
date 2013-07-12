<?php

namespace Plates\Extension;

class Asset extends Base
{
    public $methods = ['asset'];
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

            echo $path['dirname'] . '/' . $path['filename'] . '.' . $last_updated . '.' . $path['extension'];

        } else {

            echo $url;
        }
    }
}
