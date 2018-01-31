<?php

namespace League\Plates;

/** API for the Plates system. This wraps the container and allows extensions to add methods for syntactic sugar. */
final class Engine
{
    private $container;

    public function __construct(Util\Container $container = null) {
        $this->container = $container ?: Util\Container::create(['engine_methods' => []]);
    }

    /** Create a configured engine and set the base dir and extension optionally */
    public static function create($base_dir, $ext = null) {
        return self::createWithConfig(array_filter([
            'base_dir' => $base_dir,
            'ext' => $ext
        ]));
    }

    /** Create a configured engine and pass in an array to configure after extension registration */
    public static function createWithConfig(array $config = []) {
        $plates = new self();

        $plates->register(new PlatesExtension());
        $plates->register(new Extension\Data\DataExtension());
        $plates->register(new Extension\Path\PathExtension());
        $plates->register(new Extension\RenderContext\RenderContextExtension());
        $plates->register(new Extension\LayoutSections\LayoutSectionsExtension());
        $plates->register(new Extension\Folders\FoldersExtension());

        $plates->addConfig($config);

        return $plates;
    }

    /** @return string */
    public function render(string $template_name, array $data = [], array $attributes = []): string {
        return $this->container->get('renderTemplate')->renderTemplate(new Template(
            $template_name,
            $data,
            $attributes
        ));
    }

    public function addMethods(array $methods) {
        $this->container->merge('engine_methods', $methods);
    }
    public function __call($method, array $args) {
        $methods = $this->container->get('engine_methods');
        if (isset($methods[$method])) {
            return $methods[$method]($this, ...$args);
        }

        throw new \BadMethodCallException("No method {$method} found for engine.");
    }

    public function register(Extension $extension) {
        $extension->register($this);
    }

    public function getContainer() {
        return $this->container;
    }
}
