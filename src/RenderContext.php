<?php

namespace League\Plates;

use BadMethodCallException;

final class RenderContext
{
    public $render;
    public $template;

    private $func_stack;

    public function __construct(
        RenderTemplate $render,
        Template $template,
        $func_stack = null
    ) {
        $this->render = $render;
        $this->template = $template;
        $this->func_stack = $func_stack ?: Util\stack([RenderContext\platesFunc()]);
    }

    public function __get($name) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot access ' . $name . ' because no func stack has been setup.');
        }

        $func_stack = $this->func_stack;
        return $prop_stack(new RenderContext\FuncArgs(
            $this->render,
            $this->template,
            $name
        ));
    }

    public function __set($name, $value) {
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
            '__invoke',
            $args
        ));
    }

    public static function factory($func_stack = null) {
        return function(RenderTemplate $render, Template $template) use (
            $func_stack
        ) {
            return new self(
                $render,
                $template,
                $func_stack
            );
        };
    }
}
