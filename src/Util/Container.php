<?php

namespace League\Plates\Util;

final class Container
{
    private $boxes = [];
    private $cached = [];

    public static function create(array $defs) {
        $c = new self();
        foreach ($defs as $key => $val) {
            $c->add($key, $val);
        }
        return $c;
    }

    public function add($id, $value) {
        if (array_key_exists($id, $this->cached)) {
            throw new \LogicException('Cannot add service after it has been frozen.');
        }
        $this->boxes[$id] = [$value, $value instanceof \Closure ? true : false];
    }

    public function addComposed($id, callable $define_composers) {
        $this->add($id, function($c) use ($id) {
            return compose(...array_values($c->get($id . '.composers')));
        });
        $this->add($id . '.composers', $define_composers);
    }

    public function wrapComposed($id, callable $wrapped) {
        $this->wrap($id . '.composers', $wrapped);
    }

    public function addStack($id, callable $define_stack) {
        $this->add($id, function($c) use ($id) {
            return stack($c->get($id . '.stack'));
        });
        $this->add($id . '.stack', $define_stack);
    }

    public function wrapStack($id, callable $wrapped) {
        $this->wrap($id . '.stack', $wrapped);
    }

    public function merge($id, array $values) {
        $old = $this->get($id);
        $this->add($id, array_merge($old, $values));
    }

    public function wrap($id, $wrapper) {
        if (!$this->has($id)) {
            throw new \LogicException('Cannot wrap service ' . $id . ' that does not exist.');
        }
        $box = $this->boxes[$id];
        $this->boxes[$id] = [function($c) use ($box, $wrapper) {
            return $wrapper($this->unbox($box, $c), $c);
        }, true];
    }

    public function get($id) {
        if (array_key_exists($id, $this->cached)) {
            return $this->cached[$id];
        }
        if (!$this->has($id)) {
            throw new \LogicException('Cannot retrieve service ' . $id . ' that does exist.');
        }
        $result = $this->unbox($this->boxes[$id], $this);
        if ($this->boxes[$id][1]) { // only cache services
            $this->cached[$id] = $result;
        }
        return $result;
    }

    public function has($id) {
        return array_key_exists($id, $this->boxes);
    }

    private function unbox($box, Container $c) {
        list($value, $is_factory) = $box;
        if (!$is_factory) {
            return $value;
        }
        return $value($c);
    }
}
