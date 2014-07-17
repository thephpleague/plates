<?php

namespace League\Plates;

/**
 * Stores all the template environment settings.
 */
class Engine
{
    /**
     * Path to default template directory.
     * @var string
     */
    protected $directory;

    /**
     * File extension used by templates.
     * @var string
     */
    protected $fileExtension;

    /**
     * Collection of template folders.
     * @var array
     */
    protected $folders;

    /**
     * Collection of available functions.
     * @var array
     */
    protected $functions = array();

    /**
     * Optional template code compiler.
     * @var Compiler\Compiler
     */
    protected $compiler;

    /**
     * Collection of variables shared by all templates.
     * @var array
     */
    protected $sharedVariables = array();

    /**
     * Collection of preassigned template variables.
     * @var array
     */
    protected $templateVariables = array();

    /**
     * Create new Engine instance and load the default extensions.
     * @param string $directory
     * @param string $fileExtension
     */
    public function __construct($directory = null, $fileExtension = 'php')
    {
        $this->setDirectory($directory);
        $this->setFileExtension($fileExtension);
        $this->loadExtensions(array(
            new Extension\Batch,
            new Extension\Escape,
            new Extension\Nest
        ));
    }

    /**
     * Set path to templates directory.
     * @param  string|null $directory Pass null to disable the default directory.
     * @return Engine
     */
    public function setDirectory($directory)
    {
        if (!is_null($directory) and !is_string($directory)) {
            throw new \LogicException(
                'The directory must be a string or null, ' . gettype($directory) . ' given.'
            );
        }

        if (is_string($directory) and !is_dir($directory)) {
            throw new \LogicException(
                'The specified directory "' . $directory . '" does not exist.'
            );
        }

        $this->directory = $directory;

        return $this;
    }

    /**
     * Get path to templates directory.
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set the template file extension.
     * @param  string|null $fileExtension Pass null to manually set it each time.
     * @return Engine
     */
    public function setFileExtension($fileExtension)
    {
        if (!is_string($fileExtension) and !is_null($fileExtension)) {
            throw new \LogicException(
                'The file extension must be a string or null, ' . gettype($fileExtension) . ' given.'
            );
        }

        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * Get the template file extension.
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Enable the optional template code compiler.
     * @param  string $cacheDirectory
     * @return Engine
     */
    public function enableCompiler($cacheDirectory = null)
    {
        $this->compiler = new Compiler\Compiler($this, $cacheDirectory);

        return $this;
    }

    /**
     * Disable the optional template code compiler.
     * @return Engine
     */
    public function disableCompiler()
    {
        $this->compiler = null;

        return $this;
    }

    /**
     * Add a new template folder for grouping templates under different namespaces.
     * @param  string $namespace
     * @param  string $directory
     * @return Engine
     */
    public function addFolder($namespace, $directory, $fallback = false)
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
            throw new \LogicException('The folder namespace "' . $namespace . '" is already being used.');
        }

        $this->folders[$namespace] = array(
            'path' => $directory,
            'fallback' => $fallback
        );

        return $this;
    }

    /**
     * Add directories where templates can be found.
     * @param  array  $directories
     * @return Engine
     */
    public function addFolders(array $directories = array())
    {
        foreach ($directories as $namespace => $directory) {
            $this->addFolder($namespace, $directory);
        }

        return $this;
    }

    /**
     * Add shared template data.
     * @param  mixed      $argument_1;
     * @param  array|null $argument_2;
     * @return Engine
     */
    public function shareData($argument_1, array $argument_2 = null)
    {
        if (is_null($argument_2)) {
            $templates = null;
            $data = $argument_1;
        } else {
            $templates = is_string($argument_1) ? array($argument_1) : $argument_1;
            $data = $argument_2;
        }

        if (!is_array($templates) and !is_null($templates)) {
            throw new \LogicException(
                'The templates variable must be an array or a string, ' . gettype($templates) . ' given.'
            );
        }

        if (!is_array($data)) {
            throw new \LogicException(
                'The data variable must be an array, ' . gettype($data) . ' given.'
            );
        }

        if (is_null($templates)) {
            $this->sharedVariables = array_merge($this->sharedVariables, $data);
        } elseif (is_array($templates)) {
            foreach ($templates as $template) {
                if (isset($this->templateVariables[$template])) {
                    $this->templateVariables[$template] = array_merge($this->templateVariables[$template], $data);
                } else {
                    $this->templateVariables[$template] = $data;
                }
            }
        }

        return $this;
    }

    /**
     * Get shared template data.
     * @return array
     */
    public function getSharedData($name)
    {
        if (isset($this->templateVariables[$name])) {
            return array_merge($this->sharedVariables, $this->templateVariables[$name]);
        } else {
            return $this->sharedVariables;
        }
    }

    /**
     * Register a new template function.
     * @param  string   $name;
     * @param  callback $callback;
     * @param  booleanv $raw;
     * @return Engine
     */
    public function registerFunction($name, $callback, $raw = false)
    {
        $this->functions[$name] = new TemplateFunction($name, $callback, $raw);

        return $this;
    }

    /**
     * Register a new raw template function.
     * @param  string   $name;
     * @param  callback $callback;
     * @return Engine
     */
    public function registerRawFunction($name, $callback)
    {
        $this->registerFunction($name, $callback, true);
    }

    /**
     * Register a new escaped template function.
     * @param  string   $name;
     * @param  callback $callback;
     * @return Engine
     */
    public function registerEscapedFunction($name, $callback)
    {
        $this->registerFunction($name, $callback);
    }

    /**
     * Drop/remove a existing template function.
     * @param  string   $name;
     * @return Engine
     */
    public function dropFunction($name)
    {
        if (!$this->functionExists($function)) {
            throw new \LogicException(
                'Function "' . $function . '" not found.'
            );
        }

        unset($this->functions[$name]);

        return $this;
    }

    /**
     * Get an existing template function.
     * @param  string $function
     * @return array
     */
    public function getFunction($function)
    {
        if (!is_string($function) or empty($function) or !is_callable($function, true)) {
            throw new \LogicException('Not a valid template function name.');
        }

        if (!$this->doesFunctionExist($function)) {
            throw new \LogicException('The template function "' . $function . '" was not found.');
        }

        return $this->functions[$function];
    }

    /**
     * Get array of all existing template functions.
     * @return array
     */
    public function getAllFunctions()
    {
        return $this->functions;
    }

    /**
     * Check if a template function exists.
     * @param  string  $function
     * @return boolean
     */
    public function doesFunctionExist($function)
    {
        return isset($this->functions[$function]);
    }

    /**
     * Load an extension and make additional functions available within templates.
     * @param  Extension\ExtensionInterface $extension
     * @return Engine
     */
    public function loadExtension(Extension\ExtensionInterface $extension)
    {
        $extension->register($this);

        return $this;
    }

    /**
     * Load multiple extensions.
     * @param  array  $extensions
     * @return Engine
     */
    public function loadExtensions(array $extensions = array())
    {
        foreach ($extensions as $extension) {
            $this->loadExtension($extension);
        }

        return $this;
    }

    /**
     * Parse the folder, filename and extension from template name.
     * @param  string $name
     * @return array
     */
    public function getParsedTemplateName($name)
    {
        if (!is_string($name)) {
            throw new \LogicException('The template name must be a string, ' . gettype($name) . ' given.');
        }

        $info = array();

        $parts = explode('::', $name);

        if (count($parts) === 1) {

            if (is_null($this->directory)) {
                throw new \LogicException('The default directory has not been defined.');
            }

            if ($parts[0] === '') {
                throw new \LogicException('The template name cannot be an empty.');
            }

            $info['folder_name'] = null;
            $info['folder_path'] = null;
            $info['folder_fallback'] = null;
            $info['file'] = $parts[0];

        } elseif (count($parts) === 2) {

            if ($parts[0] === '') {
                throw new \LogicException('The template name "' . $name . '" is not valid.');
            }

            if ($parts[1] === '') {
                throw new \LogicException('The template name "' . $name . '" is not valid.');
            }

            if (!isset($this->folders[$parts[0]])) {
                throw new \LogicException('The folder "' . $parts[0] . '" does not exist.');
            }

            $info['folder_name'] = $parts[0];
            $info['folder_path'] = $this->folders[$parts[0]]['path'];
            $info['folder_fallback'] = $this->folders[$parts[0]]['fallback'];
            $info['file'] = $parts[1];

        } else {
            throw new \LogicException('The template name "' . $name . '" is not valid.');
        }

        if (!is_null($this->fileExtension)) {
            $info['file'] .= '.' . $this->fileExtension;
        }

        return $info;
    }

    /**
     * Determine the file path of a template.
     * @param  string $name
     * @return string
     */
    public function getTemplatePath($name)
    {
        $info = $this->getParsedTemplateName($name);

        if (is_null($info['folder_name'])) {
            $path = $this->directory . DIRECTORY_SEPARATOR . $info['file'];
        } else {
            $path = $info['folder_path'] . DIRECTORY_SEPARATOR . $info['file'];

            if (!is_file($path) and
                $info['folder_fallback'] and
                is_file($this->directory . DIRECTORY_SEPARATOR . $info['file'])
            ) {
                $path = $this->directory . DIRECTORY_SEPARATOR . $info['file'];
            }
        }

        return $path;
    }

    /**
     * Determine template include path.
     * @param  string $name
     * @return string
     */
    public function getTemplateRenderPath($name)
    {
        $path = $this->getTemplatePath($name);

        if (!is_file($path)) {
            throw new \LogicException(
                'The specified template "' . $name . '" could not be found at "' . $path . '".'
            );
        }

        if ($this->compiler) {
            return $this->compiler->compile($path);
        } else {
            return $path;
        }
    }

    /**
     * Determine if a template exists.
     * @param  string  $name
     * @return boolean
     */
    public function exists($name)
    {
        return is_file($this->getTemplatePath($name));
    }

    /**
     * Creates a new template.
     * @return Template
     */
    public function make($name)
    {
        return new Template($this, $name);
    }

    /**
     * Creates a new template and renders it.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, array $data = array())
    {
        return $this->make($name)->render($data);
    }
}
