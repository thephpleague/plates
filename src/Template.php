<?php

namespace League\Plates;

/** Template value object */
final class Template
{
    public $name;
    public $data;
    public $context;
    public $content;

    public function __construct(
        $name,
        array $data = [],
        array $context = [],
        Content $content = null
    ) {
        $this->name = $name;
        $this->data = $data;
        $this->context = $context;
        $this->content = $content ?: Content\StringContent::empty();
    }

    public function addData(array $data) {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
    public function addContext(array $context) {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
}
