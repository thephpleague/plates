<?php

namespace Plates\Extension;

class Asset
{
    public $methods = array('asset');
    public $engine;
    public $template;
    public $path;
    public $filenameMethod;

    public function __construct($path, $filenameMethod = false)
    {
        $this->path = rtrim($path, '/');
        $this->filenameMethod = $filenameMethod;
    }

    public function asset($url)
    {
        $filePath = $this->path . '/' .  ltrim($url, '/');

        if (!file_exists($filePath)) {
            throw new \LogicException('Unable to locate the asset "' . $url . '" in the "' . $this->path . '" directory.');
        }

        $lastUpdated = filemtime($filePath);
        $pathInfo = pathinfo($url);

        if ($pathInfo['dirname'] === '.') {
            $directory = '';
        } elseif ($pathInfo['dirname'] === '/') {
            $directory = '/';
        } else {
            $directory = $pathInfo['dirname'] . '/';
        }

        if ($this->filenameMethod) {
            return $directory . $pathInfo['filename'] . '.' . $lastUpdated . '.' . $pathInfo['extension'];
        } else {
            return $directory . $pathInfo['filename'] . '.' . $pathInfo['extension'] . '?v=' . $lastUpdated;
        }
    }
}
