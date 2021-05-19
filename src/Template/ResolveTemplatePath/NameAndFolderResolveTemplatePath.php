<?php

namespace League\Plates\Template\ResolveTemplatePath;

use League\Plates\Exception\TemplateNotFound;
use League\Plates\Template\Name;
use League\Plates\Template\ResolveTemplatePath;
use LogicException;

/** Resolves the path from the logic in the Name class which resolves via folder lookup, and then the default directory */
final class NameAndFolderResolveTemplatePath implements ResolveTemplatePath
{
    public function __invoke(Name $name): string
    {
        $path = $this->resolvePath($name);
        if (is_file($path)) {
            return $path;
        }

        throw new TemplateNotFound(
            $name->getName(),
            [$path],
            'The template "'.$name->getName().'" could not be found at "'.$path.'".'
        );
    }

    public function exists(Name $name): bool
    {
        $path = $this->resolvePath($name);
        if (is_file($path)) {
            return true;
        }

        return false;
    }

    public function resolvePath(Name $name): string
    {
        $namespace = $name->getNamespace();
        $path = $name->getPath(false);
        $defaultDirectory = $this->getDefaultDirectory($name);

        if (is_null($namespace)) {
            return $this->normalizePath("{$defaultDirectory}/{$path}", $name);
        }

        $folder = $name->getEngine()->getFolders()->get($namespace);
        $path = $this->normalizePath($path, $name);
        $fullPath = "{$folder->getPath()}/{$path}";

        if (
            !is_file($fullPath)
            && $folder->getFallback()
            && is_file("{$defaultDirectory}/{$path}")
        ) {
            return "{$defaultDirectory}/{$path}";
        }


        return $fullPath;
    }

    protected function normalizePath($path, Name $name): string
    {
        if (!is_null($name->getEngine()->getFileExtension())) {
            $path .= '.'.$name->getEngine()->getFileExtension();
        }

        return $path;
    }

    /**
     * Get the default templates directory.
     * @return string
     */
    protected function getDefaultDirectory(Name $name)
    {
        $directory = $name->getEngine()->getDirectory();

        if (is_null($directory)) {
            throw new LogicException(
                'The template name "'.$name->getName().'" is not valid. '.
                'The default directory has not been defined.'
            );
        }

        return $directory;
    }
}
