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
    return function(...$args) use ($funcs) {
        $next = end($args);
        $args = array_slice($args, 0, -1);
        $funcs[] = $next;
        $next = stack($funcs);
        return $next(...$args);
    };
}

function compose(array $funcs, $multi = false) {
    if (!$multi) {
        return function($arg) use ($funcs) {
            return array_reduce($funcs, function($acc, $func) {
                return $func($acc);
            }, $arg);
        };
    }

    return function(...$args) use ($funcs) {
        return array_reduce($funcs, function($acc, $func) {
            return $func(...$acc);
        }, $args);
    };
}


function joinPath(array $parts, $sep = DIRECTORY_SEPARTOR) {
    return array_reduce($parts, function($acc, $part) use ($sep) {
        if ($acc === null) {
            return rtrim($part, $sep);
        }

        return $acc . $sep . ltrim($part, $sep);
    }, null);
}
