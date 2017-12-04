<?php

namespace League\Plates\Util;

final class Container
{
    private $boxes = [];
    private $cached = [];

    public function add($id, $value) {
        if (array_key_exists($id, $this->cached)) {
            throw new \LogicException('Cannot add service after it has been frozen.');
        }
        $this->boxes[$id] = [$value, $value instanceof \Closure ? true : false];
    }
    public function merge($id, array $values) {
        $old = $this->get($id);
        $this->add($id, array_merge($old, $values));
    }
    public function wrap($id, $wrapper) {
        if (!$this->has($id)) {
            throw new \LogicException('Cannot wrap a service that does not exist.');
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
            throw new \LogicException('Cannot retrieve service that does exist.');
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
