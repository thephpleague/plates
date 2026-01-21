<?php

namespace League\Plates\Template;

use League\Plates\Engine;
use LogicException;

/**
 * A template name.
 */
class Name
{
    const NAMESPACE_DELIMITER = '::';

    /**
     * Hint path delimiter value.
     *
     * @var string
     */
    const HINT_PATH_DELIMITER = '::';

    /**
     * Instance of the template engine.
     * @var Engine
     */
    protected $engine;

    /**
     * The original name.
     * @var string
     */
    protected $name;

    /**
     * The parsed namespace
     */
    protected $namespace;

    protected $folder;

    /**
     * The parsed template path.
     * @var string
     */
    protected $path;

    /**
     * Create a new Name instance.
     * @param Engine $engine
     * @param string $name
     */
    public function __construct(Engine $engine, $name)
    {
        $this->setEngine($engine);
        $this->setName($name);
    }

    /**
     * Set the engine.
     * @param  Engine $engine
     * @return Name
     */
    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * Get the engine.
     * @return Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Set the original name and parse it.
     * @param  string $name
     * @return Name
     */
    public function setName($name)
    {
        $this->name = $name;

        $parts = explode(static::NAMESPACE_DELIMITER, $this->name);

        if (count($parts) === 1) {
            $this->setPath($parts[0]);
        } elseif (count($parts) === 2) {
            $this->setNamespace($parts[0]);
            $this->setPath($parts[1]);
        } else {
            throw new LogicException(
                'The template name "' . $this->name . '" is not valid. ' .
                'Do not use the folder namespace separator "::" more than once.'
            );
        }

        return $this;
    }

    /**
     * Get the original name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the parsed template folder.
     * @param  string $namespace
     * @return Name
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the parsed template folder.
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @deprecated
     */
    public function getFolder()
    {
        if ($this->getNamespace()) {
            return $this->getEngine()->getFolders()->get($this->getNamespace());
        }
        return null;
    }

    /**
     * Set the parsed template file.
     * @param  string $path
     * @return Name
     */
    public function setPath($path)
    {
        if ($path === '') {
            throw new LogicException(
                'The template name "' . $this->name . '" is not valid. ' .
                'The template name cannot be empty.'
            );
        }

        $this->path = $path;

        return $this;
    }

    public function getFile()
    {
        $file = $this->path;
        if (!is_null($this->getEngine()->getFileExtension())) {
            $file .= '.'.$this->getEngine()->getFileExtension();
        }
        return $file;
    }

    /**
     * Resolve template path or
     * Get the parsed template file.
     * @return string
     */
    public function getPath($resolve = true )
    {
        if ($resolve) {
            return $this->engine->path($this);
        }
        return $this->path;
    }

    /**
     * Check if template path exists.
     * @return boolean
     */
    public function doesPathExist()
    {
        return $this->engine->exists($this);
    }
}
