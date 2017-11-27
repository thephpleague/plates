<?php

namespace League\Plates\Content;

use League\Plates;

class LazyContent implements Plates\Content
{
    private $build_content;
    private $content;

    public function __construct(callable $build_content) {
        $this->build_content = $build_content;
    }

    public static function fromTemplate(Plates\Template $template) {
        return new self([$template, 'getContent']);
    }

    public function __toString() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = (string) call_user_func($this->build_content);

        return $this->content;
    }
}
