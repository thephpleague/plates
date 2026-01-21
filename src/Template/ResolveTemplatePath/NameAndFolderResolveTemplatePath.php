<?php

namespace League\Plates\Template\ResolveTemplatePath;

use League\Plates\Exception\TemplateNotFound;
use League\Plates\Template\Name;
use League\Plates\Template\ResolveTemplatePath;
use LogicException;

/** Resolves the path from the logic in the Name class which resolves via folder lookup, and then the default directory */
final class NameAndFolderResolveTemplatePath implements ResolveTemplatePath
{
    /**
     * Identifier of the main namespace.
     *
     * @var string
     */
    const MAIN_NAMESPACE = '__main__';

    /**
     * Collection of template folders.
     */
    protected $paths = [];

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

    public function resolvePath(Name $name)
    {
        $namespace = $this->normalizeNamespace($name->getNamespace());
        $shortPath = $name->getPath(false);
        $shortPath = $this->normalizePath($shortPath, $name);

        $fullPath = null;
        $paths = $this->getPaths($namespace);
        foreach ($paths as $path) {
            $fullPath = $path.'/'.$shortPath;
            if (is_file($fullPath)) {
                return $fullPath;
            }
        }

        $defaultDirectory = $this->getDefaultDirectory($name);

        return "{$defaultDirectory}/{$shortPath}";
    }

    /**
     * Returns the paths to the templates.
     */
    public function getPaths(string $namespace = self::MAIN_NAMESPACE): array
    {
        $namespace = $this->normalizeNamespace($namespace);

        return $this->paths[$namespace] ?? [];
    }

    /**
     * Returns the path namespaces.
     *
     * The main namespace is always defined.
     */
    public function getNamespaces(): array
    {
        return array_keys($this->paths);
    }

    /**
     * @param string|array $paths A path or an array of paths where to look for templates
     */
    public function setPaths($paths, string $namespace = self::MAIN_NAMESPACE): void
    {
        if (!\is_array($paths)) {
            $paths = [$paths];
        }

        $this->paths[$namespace] = [];
        foreach ($paths as $path) {
            $this->addPath($path, $namespace);
        }
    }

    /**
     * @throws \Exception
     */
    public function addPath(string $path, string $namespace = self::MAIN_NAMESPACE)
    {
        if (!is_dir($path)) {
            throw new \Exception(sprintf('The "%s" directory does not exist.', $path));
        }

        $this->paths[$namespace][] = rtrim($path, '/\\');
    }

    public function prependPath(string $path, string $namespace = self::MAIN_NAMESPACE): void
    {
        if (!is_dir($path)) {
            throw new \Exception(sprintf('The "%s" directory does not exist.', $path));
        }

        $path = rtrim($path, '/\\');

        if (!isset($this->paths[$namespace])) {
            $this->paths[$namespace][] = $path;
        } else {
            array_unshift($this->paths[$namespace], $path);
        }
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

    protected function normalizeNamespace($namespace = self::MAIN_NAMESPACE): string
    {
        if (empty($namespace)) {
            return self::MAIN_NAMESPACE;
        }

        return $namespace;
    }
}
