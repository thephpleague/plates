<?php

namespace League\Plates;

use BadMethodCallException;

final class RenderContext
{
    private $prop_stack;
    private $func_stack;

    private $render;
    private $template;

    public function __construct(
        RenderTemplate $render,
        Template $template,
        $prop_stack = null,
        $func_stack = null
    ) {
        $this->render = $render;
        $this->template = $template;

        $this->prop_stack = $prop_stack;
        $this->func_stack = $func_stack;
    }

    public function __get($name) {
        if (!$this->prop_stack) {
            throw new BadMethodCallException('Cannot access ' . $name . ' because no prop stack was setup.');
        }

        $prop_stack = $this->prop_stack;
        return $prop_stack(new RenderContext\PropArgs(
            $this->render,
            $this->template,
            $name
        ));
    }

    public function __set($name) {
        throw new BadMethodCallException('Cannot set ' . $name . ' on this render context.');
    }

    public function __call($name, array $args) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot call ' . $name . ' because no func stack has been setup.');
        }

        $func_stack = $this->func_stack;
        return $func_stack(new RenderContext\FuncArgs(
            $this->render,
            $this->template,
            $name,
            $args
        ));
    }

    public function __invoke(...$args) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot invoke the render context because no func stack has been setup.');
        }

        $func_stack = $this->func_stack;
        return $func_stack(new RenderContext\FuncArgs(
            $this->render,
            $this->template,
            null,
            $args
        ));
    }

    public static function createFactory($prop_stack = null, $func_stack = null) {
        return function(RenderTemplate $render, Template $template) use (
            $prop_stack,
            $func_stack
        ) {
            return new self(
                $render,
                $template,
                $prop_stack,
                $func_stack
            );
        };
    }
}
