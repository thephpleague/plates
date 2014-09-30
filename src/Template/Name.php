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
     * The original name.
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

        $this->parseName();
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
     * Get the parsed template folder.
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Get the parsed template file.
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Resolve template path.
     * @return string
     */
    public function getPath()
    {
        if (is_null($this->folder)) {

            $path = $this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file;

        } else {

            $path = $this->folder->getPath() . DIRECTORY_SEPARATOR . $this->file;

            if (!is_file($path) and $this->folder->getFallback() and is_file($this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file)) {

                $path = $this->engine->getDirectory() . DIRECTORY_SEPARATOR . $this->file;
            }
        }

        return $path;
    }

    /**
     * Check if template path exists.
     * @return boolean
     */
    public function exists()
    {
        return is_file($this->getPath());
    }

    /**
     * Parse name to determine template folder and filename.
     */
    protected function parseName()
    {
        $parts = explode('::', $this->name);

        if (count($parts) === 1) {

            if (is_null($this->engine->getDirectory())) {
                $this->parseError('The default directory has not been defined.');
            }

            if ($parts[0] === '') {
                $this->parseError('The template name cannot be empty.');
            }

            $this->file = $parts[0];

        } elseif (count($parts) === 2) {

            if ($parts[0] === '') {
                $this->parseError('The folder name cannot be empty.');
            }

            if ($parts[1] === '') {
                $this->parseError('The template name cannot be empty.');
            }

            if (!$this->engine->getFolders()->exists($parts[0])) {
                $this->parseError('The folder "' . $parts[0] . '" does not exist.');
            }

            $this->folder = $this->engine->getFolders()->get($parts[0]);
            $this->file = $parts[1];

        } else {
            $this->parseError('Do not use the folder namespace seperator "::" more than once.');
        }

        if (!is_null($this->engine->getFileExtension())) {
            $this->file .= '.' . $this->engine->getFileExtension();
        }
    }

    /**
     * Handle a parse error.
     * @param  string $message
     */
    public function parseError($message)
    {
        throw new LogicException('The template name "' . $this->name . '" is not valid. ' . $message);
    }
}
