<?php

namespace Plates;

class Engine
{
    protected $directory;
    protected $fileExtension;
    protected $folders;
    protected $methods;

    public function __construct($directory = null, $fileExtension = 'php')
    {
        $this->setDirectory($directory);
        $this->setFileExtension($fileExtension);

        $this->loadExtension(new Extension\Escape);
    }

    public function setDirectory($directory)
    {
        if (!is_null($directory) and !is_string($directory)) {
            throw new \LogicException('The directory must be a string or null, ' . gettype($directory) . ' given.');
        }

        if (is_string($directory) and !is_dir($directory)) {
            throw new \LogicException('The specified directory "' . $directory . '" does not exist.');
        }

        $this->directory = $directory;
    }

    public function setFileExtension($fileExtension)
    {
        if (!is_string($fileExtension)) {
            throw new \LogicException('The file extension must be a string, ' . gettype($fileExtension) . ' given.');
        }

        $this->fileExtension = $fileExtension;
    }

    public function addFolder($namespace, $directory)
    {
        if (!is_string($namespace)) {
            throw new \LogicException('The namespace must be a string, ' . gettype($namespace) . ' given.');
        }

        if (!is_string($directory)) {
            throw new \LogicException('The directory must be a string, ' . gettype($directory) . ' given.');
        }

        if (!is_dir($directory)) {
            throw new \LogicException('The specified directory "' . $directory . '" does not exist.');
        }

        if (isset($this->folders[$namespace])) {
            throw new \LogicException('Folder conflict detected. The namespace "' . $namespace . '" is already being used.');
        }

        $this->folders[$namespace] = $directory;
    }

    public function loadExtension($extension)
    {
        if (!isset($extension->methods)) {
            throw new \LogicException('The extension "' . get_class($extension) . '" has no public methods parameter defined.');
        }

        if (!is_array($extension->methods)) {
            throw new \LogicException('The "' . get_class($extension) . '" method definition must be an array, ' . gettype($extension->methods) . ' given.');
        }

        if (count($extension->methods) === 0) {
            throw new \LogicException('The extension must have at least one method defined.');
        }

        foreach ($extension->methods as $method) {

            if (!is_string($method)) {
                throw new \LogicException('The extension methods must be a string, ' . gettype($method) . ' given.');
            }

            if ($method === '') {
                throw new \LogicException('The extension methods cannot be an empty string.');
            }

            if (isset($this->methods[$method])) {
                throw new \LogicException('Extension conflict detected. The method "' . $method . '" has already been defined in the "' . get_class($this->methods[$method]) . '" extension.');
            }

            $this->methods[$method] = $extension;
        }
    }

    public function getExtension($method)
    {
        if (!is_string($method)) {
            throw new \LogicException('The extension method must be a string, ' . gettype($method) . ' given.');
        }

        if (!isset($this->methods[$method])) {
            throw new \LogicException('No extensions with the method "' . $method . '" were found.');
        }

        return $this->methods[$method];
    }

    public function resolvePath($path)
    {
        if (!is_string($path)) {
            throw new \LogicException('The path must be a string, ' . gettype($path) . ' given.');
        }

        $parts = explode('::', $path);

        if (count($parts) < 1 or count($parts) > 2) {
            throw new \LogicException('The path "' . $path . '" is not a valid path format.');
        }

        if (count($parts) === 1) {

            if (is_null($this->directory)) {
                throw new \LogicException('The default directory has not been defined.');
            }

            if ($parts[0] === '') {
                throw new \LogicException('The path cannot be an empty.');
            }

            $filePath = $this->directory . '/' . $parts[0] . '.' . $this->fileExtension;

        } else if (count($parts) === 2) {

            if ($parts[0] === '') {
                throw new \LogicException('The path "' . $path . '" is not a valid path format.');
            }

            if ($parts[1] === '') {
                throw new \LogicException('The path "' . $path . '" is not a valid path format.');
            }

            if (!isset($this->folders[$parts[0]])) {
                throw new \LogicException('The folder "' . $parts[0] . '" does not exist.');
            }

            $filePath = $this->folders[$parts[0]] . '/' . $parts[1] . '.' . $this->fileExtension;
        }

        if (!is_file($filePath)) {
            throw new \LogicException('The specified template "' . $path . '" could not be found at "' . $filePath . '".');
        }

        return $filePath;
    }
}
