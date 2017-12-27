<?php

namespace League\Plates\Extension\LayoutSections;

/** A simple store for managing section content. This needs to be a mutable object
    so that references can be shared across templates. */
final class Sections
{
    private $sections;

    public function __construct(array $sections = []) {
        $this->sections = $sections;
    }

    public function add($name, $content) {
        $this->sections[$name] = $content;
    }

    public function append($name, $content) {
        $this->sections[$name] = ($this->get($name) ?: '') . $content;
    }

    public function prepend($name, $content) {
        $this->sections[$name] = $content . ($this->get($name) ?: '');
    }

    public function clear($name) {
        unset($this->sections[$name]);
    }

    public function get($name) {
        return array_key_exists($name, $this->sections)
            ? $this->sections[$name]
            : null;
    }

    public function merge(Sections $sections) {
        return new self(array_merge($this->sections, $sections->sections));
    }
}
