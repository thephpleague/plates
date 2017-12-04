<?php

namespace League\Plates;

/** API for the Plates system */
final class Engine
{
    private $container;

    public function __construct($context = []) {
        $this->container = new Util\Container();

        $this->container->add('engine_methods', []);
        $this->container->add('config', array_merge([
            'render_context_var_name' => 'v',
            'ext' => 'phtml',
            'base_dir' => null,
            'escape_encoding' => null,
            'escape_flags' => null,
        ], $context));
        $this->container->add('include', function($c) {
            return Template\phpInclude();
        });
        $this->container->add('funcs', function($c) {
            return [RenderContext\platesFunc($c->get('config'))];
        });
        $this->container->add('resolve_name_stack', function($c) {
            return [Template\platesResolveName($c->get('config'))];
        });
        $this->container->add('resolve_data_stack', function() {
            return Util\id();
        });
        $this->container->add('createRenderContext', function($c) {
            return RenderContext::factory(Util\stack($c->get('funcs')));
        });
        $this->container->add('render', function($c) {
            $rt = new RenderTemplate\PlatesRenderTemplate(
                Util\stack($c->get('resolve_name_stack')),
                Util\stack($c->get('resolve_data_stack')),
                $c->get('include'),
                $c->get('createRenderContext'),
                $c->get('config')['render_context_var_name']
            );
            $rt = new RenderTemplate\LayoutRenderTemplate($rt);
            return $rt;
        });
    }

    /** @return string */
    public function render($template_name, array $data) {
        $template = new Template($template_name, $data, [
            'config' => $this->getConfig(),
            'container' => $this->container,
        ]);
        return $this->container->get('render')->renderTemplate($template);
    }

    public function __call($method, array $args) {
        $methods = $this->get('engine_methods');
        if (isset($methods[$method])) {
            return $methods[$method]($this, ...$args);
        }

        throw new \BadMethodCallException("No method {$method} found for engine.");
    }

    public function addMethods(array $methods) {
        $this->container->merge('engine_methods', $methods);
    }
    public function addConfig(array $config) {
        $this->container->merge('config', $config);
    }
    public function getConfig() {
        $this->container->get('config');
    }

    public function get($id) {
        return $this->container->get($id);
    }
    public function has($id) {
        return $this->contianer->has($id);
    }
    public function add($id, $value) {
        $this->container->add($id, $value);
    }
    public function wrap($id, $wrapper) {
        $this->container->wrap($id, $wrapper);
    }
}
