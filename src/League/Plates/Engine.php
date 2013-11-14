<?php

namespace League\Plates;

class Engine
{
    protected $directory;
    protected $fileExtension;
    protected $folders;
    protected $functions;

    public function __construct($directory = null, $fileExtension = 'php')
    {
        $this->setDirectory($directory);
        $this->setFileExtension($fileExtension);

        // Load default extensions
        $this->loadExtension(new Extension\Escape);
        $this->loadExtension(new Extension\Batch);
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

        return $this;
    }

    public function setFileExtension($fileExtension)
    {
        if (!is_string($fileExtension) and !is_null($fileExtension)) {
            throw new \LogicException('The file extension must be a string or null, ' . gettype($fileExtension) . ' given.');
        }

        $this->fileExtension = $fileExtension;

        return $this;
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

        return $this;
    }

    public function loadExtension(Extension\ExtensionInterface $extension)
    {
        if (!is_array($extension->getFunctions())) {
            throw new \LogicException('The "' . get_class($extension) . '" getFunctions method must return an array, ' . gettype($extension->getFunctions()) . ' given.');
        }

        if (count($extension->getFunctions()) === 0) {
            throw new \LogicException('The extension "' . get_class($extension) . '" has no functions defined.');
        }

        $extension->engine = $this;

        foreach ($extension->getFunctions() as $function => $method) {

            if (!is_string($function) or empty($function) or !is_callable($function, true)) {
                throw new \LogicException('The function "' . $function . '" is not a valid function name in the "' . get_class($extension) . '" extension.');
            }

            if (!is_string($method) or empty($method) or !is_callable($method, true)) {
                throw new \LogicException('The method "' . $method . '" is not a valid method name in the "' . get_class($extension) . '" extension.');
            }

            if (isset($this->functions[$function]) or in_array($function, array('layout', 'data', 'start', 'end', 'child', 'insert', 'render'))) {
                throw new \LogicException('The function "' . $function . '" already exists and cannot be used by the "' . get_class($extension) . '" extension.');
            }

            if (!is_callable(array($extension, $method))) {
                throw new \LogicException('The method "' . $method . '" is not callable in the "' . get_class($extension) . '" extension.');
            }

            $this->functions[$function] = array($extension, $method);
        }

        return $this;
    }

    public function getFunction($function)
    {
        if (!is_string($function) or empty($function) or !is_callable($function, true)) {
            throw new \LogicException('Not a valid extension function name.');
        }

        if (!$this->functionExists($function)) {
            throw new \LogicException('No extensions with the function "' . $function . '" were found.');
        }

        return $this->functions[$function];
    }

    public function functionExists($method)
    {
        return isset($this->functions[$method]);
    }

    public function resolvePath($path)
    {
        if (!is_string($path)) {
            throw new \LogicException('The path must be a string, ' . gettype($path) . ' given.');
        }

        $parts = explode('::', $path);

        if (count($parts) === 1) {

            if (is_null($this->directory)) {
                throw new \LogicException('The default directory has not been defined.');
            }

            if ($parts[0] === '') {
                throw new \LogicException('The path cannot be an empty.');
            }

            $filePath = $this->directory . DIRECTORY_SEPARATOR . $parts[0];

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

            $filePath = $this->folders[$parts[0]] . DIRECTORY_SEPARATOR . $parts[1];

        } else {
            throw new \LogicException('The path "' . $path . '" is not a valid path format.');
        }

        if (!is_null($this->fileExtension)) {
            $filePath .= '.' . $this->fileExtension;
        }

        if (!is_file($filePath)) {
            throw new \LogicException('The specified template "' . $path . '" could not be found at "' . $filePath . '".');
        }

        return $filePath;
    }
}
