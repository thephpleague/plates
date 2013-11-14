<?php

namespace League\Plates\Extension;

class Asset implements ExtensionInterface
{
    public $engine;
    public $template;
    public $path;
    public $filenameMethod;

    public function __construct($path, $filenameMethod = false)
    {
        $this->path = rtrim($path, '/');
        $this->filenameMethod = $filenameMethod;
    }

    public function getFunctions()
    {
        return array(
            'asset' => 'cachedAssetUrl'
        );
    }

    public function cachedAssetUrl($url)
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
