<?php

namespace League\Plates\Content;

use League\Plates\Content;

class CollectionContent implements Content
{
    private $parts;

    public function __construct(array $parts) {
        $this->parts = $parts;
    }

    public function __toString() {
        return array_reduce($this->parts, function($acc, $part) {
            return $acc . $part;
        }, '');
    }
}
