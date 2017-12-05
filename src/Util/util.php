<?php

namespace League\Plates\Util;

use League\Plates\Exception\ComposeException;

function id($multi = false) {
    if ($multi) {
        return function(...$args) {
            return $args;
        };
    } else {
        return function($arg) {
            return $arg;
        };
    }
}

/** simple utility that wraps php echo which allows for stubbing out the
    echo func for testing */
function phpEcho() {
    return function($v) {
        echo $v;
    };
}

/** stack a set of functions into each other and returns the stacked func */
function stack(array $funcs) {
    return array_reduce(array_reverse($funcs), function($next, $func) {
        return function(...$args) use ($next, $func) {
            $args[] = $next;
            return $func(...$args);
        };
    }, function() {
        throw new ComposeException('No handler was able to return a result.');
    });
}

function stackGroup(array $funcs) {
    $end_next = null;
    $funcs[] = function(...$args) use (&$end_next) {
        return $end_next(...array_slice($args, 0, -1));
    };
    $next = stack($funcs);
    return function(...$args) use ($next, &$end_next) {
        $end_next = end($args);
        return $next(...array_slice($args, 0, -1));
    };
}

function joinPath(array $parts, $sep = DIRECTORY_SEPARATOR) {
    return array_reduce($parts, function($acc, $part) use ($sep) {
        if ($acc === null) {
            return rtrim($part, $sep);
        }

        return $acc . $sep . ltrim($part, $sep);
    }, null);
}
