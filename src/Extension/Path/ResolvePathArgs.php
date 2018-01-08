<?php

namespace League\Plates\Extension\Path;

use League\Plates\Template;

class ResolvePathArgs
{
    public $path;
    public $context;
    public $template;

    public function __construct($path, array $context, Template $template) {
        $this->path = $path;
        $this->context = $context;
        $this->template = $template;
    }

    public function withPath($path) {
        return new self($path, $this->context, $this->template);
    }

    public function withContext(array $context) {
        return new self($this->path, $context, $this->template);
    }

    public static function fromTemplate(Template $template) {
        return new self($template->name, [], clone $template);
    }
}
