<?php

namespace League\Plates\Compiler;

/**
 * Optional compiler that enables automatic escaping and shorter template syntax.
 */
class Compiler
{
    /**
     * Instance of the template engine.
     * @var League\Plates\Engine
     */
    private $engine;

    /**
     * Path to compiled template cache directory.
     * @var string
     */
    private $cacheDirectory;

    /**
     * Create new Compiler instance.
     * @param Engine $engine
     * @param string|null $cacheDirectory
     */
    public function __construct(\League\Plates\Engine $engine, $cacheDirectory = null)
    {
        $this->engine = $engine;
        $this->setCacheDirectory($cacheDirectory);
    }

    /**
     * Set path to cache directory.
     * @param  string|null $cacheDirectory Pass null to disable caching.
     * @return Compiler
     */
    public function setCacheDirectory($cacheDirectory)
    {
        if (!is_null($cacheDirectory) and !is_string($cacheDirectory)) {
            throw new \LogicException(
                'The cache directory must be a string or null, ' . gettype($cacheDirectory) . ' given.'
            );
        }

        if (is_string($cacheDirectory) and !is_dir($cacheDirectory)) {
            throw new \LogicException('The specified cache directory "' . $cacheDirectory . '" does not exist.');
        }

        $this->cacheDirectory = $cacheDirectory;

        return $this;
    }

    /**
     * Compile template based on compiling flags.
     * @param  string $name
     * @return string
     */
    public function compile($sourcePath)
    {
        $destinationPath = $this->getCompiledPath($sourcePath);

        if ($this->isCacheExpired($sourcePath, $destinationPath)) {
            try {

                // Get source code from template
                $sourceCode = file_get_contents($sourcePath);

                // Convert short open tags "<?" to normal open tags "<?php"
                $sourceCode = preg_replace('/<\?(?!xml|php|=)/s', '<?php', $sourceCode);

                // Parse
                $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
                $statements = $parser->parse($sourceCode);

                // Modify
                $traverser = new \PhpParser\NodeTraverser;
                $traverser->addVisitor(
                    new NodeVisitor(
                        $this->getTemplateFunctions(),
                        $this->getRawTemplateFunctions()
                    )
                );
                $statements = $traverser->traverse($statements);

                // Generate
                $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
                $sourceCode = '<?php ' . $prettyPrinter->prettyPrint($statements) . ' ?>';

                // Save to disk
                file_put_contents($destinationPath, $sourceCode);
            } catch (\PhpParser\Error $e) {
                echo 'Parse Error: ', $e->getMessage();
            }
        }

        return $destinationPath;
    }

    /**
     * Get the compiled template path based on the template name.
     * @param  string $name
     * @return string
     */
    public function getCompiledPath($sourcePath)
    {
        if ($this->cacheDirectory) {
            return $this->cacheDirectory . DIRECTORY_SEPARATOR . md5($sourcePath);
        } else {
            return tempnam(sys_get_temp_dir(), 'plates_');
        }
    }

    /**
     * Determine if the template cache has expired.
     * @param  string $sourcePath
     * @param  string $destinationPath
     * @return bool
     */
    public function isCacheExpired($sourcePath, $destinationPath)
    {
        if (is_null($this->cacheDirectory)) {
            return true;
        }

        if (!is_file($destinationPath)) {
            return true;
        }

        return filemtime($sourcePath) >= filemtime($destinationPath);
    }

    /**
     * Get array of all template function names.
     * @return array
     */
    public function getTemplateFunctions()
    {
        return array_merge(array_keys($this->engine->getAllFunctions()), array('layout', 'start', 'stop', 'section'));
    }

    /**
     * Get array of all raw template function names.
     * @return array
     */
    public function getRawTemplateFunctions()
    {
        $functions = array('section');

        foreach ($this->engine->getAllFunctions() as $function) {
            if ($function->isRaw()) {
                $functions[] = $function->getName();
            }
        }

        return $functions;
    }
}
