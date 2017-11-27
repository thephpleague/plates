<?php

namespace League\Plates\Content;

use League\Plates\Content;

class StringContent implements Content
{
    private $content;

    public function __construct($content) {
        $this->content = $content;
    }

    public static function empty() {
        return new self('');
    }

    public function __toString() {
        return (string) $this->content;
    }
}
