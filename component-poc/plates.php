<?php

namespace League\Plates;

final class ContextRegistry
{
    private static $instance;

    private $contextByName;
    private function __construct() {}

    public static function self(): self {
        return self::$instance ?? self::$instance = new self();
    }

    public function get(string $className) {
        return $this->contextByName[$className] ?? null;
    }

    public function set($context, string $className = 'default') {
        $this->contextByName[$className] = $context;
    }
}

function p(callable $fn, string $contextKey = 'default') {
    $cur_level = ob_get_level();

    try {
        ob_start();
        $fn(ContextRegistry::self()->get($contextKey));
        return ob_get_clean();
    } catch (\Throwable $e) {}

    // clean the ob stack
    while (ob_get_level() > $cur_level) {
        ob_end_clean();
    }

    throw $e;
}

function e(string $string, $flags = ENT_COMPAT | ENT_HTML401, string $encoding = 'UTF-8', bool $doubleEncode = true): string {
    return htmlspecialchars($string, $flags, $encoding, $doubleEncode);
}
