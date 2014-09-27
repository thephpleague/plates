<?php

namespace League\Plates\Template;

use League\Plates\Engine;
use LogicException;

/**
 * A template name.
 */
class Name
{
    /**
     * Instance of the template engine.
     * @var Engine
     */
    protected $engine;

    /**
     * The name as a string.
     * @var string
     */
    protected $name;

    /**
     * The parsed template folder.
     * @var Folder
     */
    protected $folder;

    /**
     * The parsed template filename.
     * @var string
     */
    protected $file;

    /**
     * Create a new Name instance.
     * @param Engine $engine
     * @param string $name
     */
    public function __construct(Engine $engine, $name)
    {
        $this->engine = $engine;
        $this->name = $name;

        $this->parse();
    }

    /**
     * Resolve template path.
     * @return string
     */
    public function path()
    {
        if (is_null($this->folder)) {

            $path = $this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file;

        } else {

            $path = $this->folder->getPath() . DIRECTORY_SEPARATOR . $this->file;

            if (!is_file($path) and $this->folder->getFallback() and is_file($this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file)) {

                $path = $this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file;
            }
        }

        if (!is_null($this->engine->getFileExtension())) {
            $path .= '.' . $this->engine->getFileExtension();
        }

        return $path;
    }

    /**
     * Check if template path exists.
     * @return boolean
     */
    public function exists()
    {
        return is_file($this->path($this->name));
    }

    /**
     * Parse name to determine template folder and filename.
     */
    protected function parse()
    {
        $parts = explode('::', $this->name);

        if (count($parts) === 1) {

            if (is_null($this->engine->getDirectory())) {
                $this->throwParseException('The default directory has not been defined.');
            }

            if ($parts[0] === '') {
                $this->throwParseException('The template name cannot be empty.');
            }

            $this->file = $parts[0];

        } elseif (count($parts) === 2) {

            if ($parts[0] === '') {
                $this->throwParseException('The folder name cannot be empty.');
            }

            if ($parts[1] === '') {
                $this->throwParseException('The template name cannot be empty.');
            }

            if (!$this->engine->getFolders()->exists($parts[0])) {
                $this->throwParseException('The folder "' . $parts[0] . '" does not exist.');
            }

            $this->folder = $this->engine->getFolders()->get($parts[0]);
            $this->file = $parts[1];

        } else {
            $this->throwParseException('Do not use the folder namespace seperator "::" more than once.');
        }
    }

    /**
     * Throws a parse exception.
     * @param  string $message
     */
    public function throwParseException($message)
    {
        throw new LogicException('The template name "' . $this->name . '" is not valid.' . $message);
    }

    /**
     * Get the name as a string.
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
