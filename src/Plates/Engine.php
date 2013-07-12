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
        $this->loadExtension(new Extension\Insert);
        $this->loadExtension(new Extension\Inheritance);
    }

    public function setDirectory($directory)
    {
        if (!is_null($directory) and !is_dir($directory)) {
            trigger_error('The specified directory "' . $directory . '" does not exist.', E_USER_ERROR);
        }

        $this->directory = $directory;
    }

    public function setFileExtension($fileExtension)
    {
        if (!is_string($fileExtension)) {
            trigger_error('The file extension must be a string, ' . gettype($fileExtension) . ' given.', E_USER_ERROR);
        }

        $this->fileExtension = $fileExtension;
    }

    public function addFolder($namespace, $directory)
    {
        if (!is_string($namespace)) {
            trigger_error('The namespace must be a string, ' . gettype($namespace) . ' given.', E_USER_ERROR);
        }

        if (!is_null($directory) and !is_dir($directory)) {
            trigger_error('The specified directory "' . $directory . '" does not exist.', E_USER_ERROR);
        }

        if (isset($this->folders[$namespace])) {
            trigger_error('Folder conflict detected. The namespace "' . $namespace . '" is already being used.', E_USER_ERROR);
        }

        $this->folders[$namespace] = $directory;
    }

    public function loadExtension(Extension\Base $extension)
    {
        if (!isset($extension->methods)) {
            trigger_error('The extension "' . get_class($extension) . '" has no public methods paramater defined.', E_USER_ERROR);
        }

        if (!is_array($extension->methods)) {
            trigger_error('The "' . get_class($extension) . '" method definition must be an array, ' . gettype($extension->methods) . ' given.', E_USER_ERROR);
        }

        foreach ($extension->methods as $method) {

            if (isset($this->methods[$method])) {
                trigger_error('Extension conflict detected. The method "' . $method . '" has already been defined in the "' . get_class($this->methods[$method]) . '" extension.', E_USER_ERROR);
            }

            $this->methods[$method] = $extension;
        }
    }

    public function getExtension($name)
    {
        if (!isset($this->methods[$name])) {
            trigger_error('No extension with the method "' . $name . '" were found.', E_USER_ERROR);
        }

        return $this->methods[$name];
    }

    public function resolvePath($path)
    {
        if (!is_string($path)) {
            trigger_error('The path must be a string, ' . gettype($path) . ' given.', E_USER_ERROR);
        }

        $parts = explode('::', $path);

        if (count($parts) === 1) {
            $filePath = $this->directory . '/' . $parts[0] . '.' . $this->fileExtension;
        } else {
            $filePath = $this->folders[$parts[0]] . '/' . $parts[1] . '.' . $this->fileExtension;
        }

        if (!is_file($filePath)) {
            trigger_error('The specified template "' . $path . '" could not be found at "' . $filePath . '".', E_USER_ERROR);
        }

        return $filePath;
    }
}
