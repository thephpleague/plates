<?php

namespace League\Plates;

/** Template value object */
class Template
{
    private $name;
    private $data;
    private $context;
    private $content;

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

    public function getName() {
        return $this->name;
    }

    public function getData() {
        return $this->data;
    }

    public function getContext() {
        return $this->context;
    }

    public function getContent() {
        return $this->content;
    }

    public function withName($name) {
        return new self($name, $this->data, $this->context, $this->content);
    }

    public function withData(array $data) {
        return new self($this->name, $data, $this->context, $this->content);
    }

    public function withContext(array $context) {
        return new self($this->name, $this->data, $context, $this->content);
    }

    public function withContent(Content $content) {
        return new self($this->name, $this->data, $this->context, $content);
    }
}
