<?php

namespace League\Plates\Extension\RenderContext;

use League\Plates;
use BadMethodCallException;

final class RenderContext
{
    private $render;
    private $ref;
    private $func_stack;

    public function __construct(
        Plates\RenderTemplate $render,
        Plates\TemplateReference $ref,
        $func_stack = null
    ) {
        $this->render = $render;
        $this->ref = $ref;
        $this->func_stack = $func_stack ?: Plates\Util\stack([platesFunc()]);
    }

    public function __get($name) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot access ' . $name . ' because no func stack has been setup.');
        }

        return $this->invokeFuncStack($name, []);
    }

    public function __set($name, $value) {
        throw new BadMethodCallException('Cannot set ' . $name . ' on this render context.');
    }

    public function __call($name, array $args) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot call ' . $name . ' because no func stack has been setup.');
        }

        return $this->invokeFuncStack($name, $args);
    }

    public function __invoke(...$args) {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot invoke the render context because no func stack has been setup.');
        }

        return $this->invokeFuncStack('__invoke', $args);
    }

    private function invokeFuncStack($name, array $args) {
        return ($this->func_stack)(new FuncArgs(
            $this->render,
            $this->ref,
            $name,
            $args
        ));
    }

    public static function factory(callable $create_render, $func_stack = null) {
        return function(Plates\TemplateReference $ref) use ($create_render, $func_stack) {
            return new self(
                $create_render(),
                $ref,
                $func_stack
            );
        };
    }
}
