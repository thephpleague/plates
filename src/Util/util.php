<?php

namespace League\Plates\Util;

use League\Plates\Exception\PlatesException;
use League\Plates\Exception\StackException;

function id() {
    return function($arg) {
        return $arg;
    };
}

/** wraps a closure in output buffering and returns the buffered
    content. */
function obWrap(callable $wrap) {
    $cur_level = ob_get_level();

    try {
        ob_start();
        $wrap();
        return ob_get_clean();
    } catch (\Exception $e) {}
      catch (\Throwable $e) {}

    // clean the ob stack
    while (ob_get_level() > $cur_level) {
        ob_end_clean();
    }

    throw $e;
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
    return array_reduce($funcs, function($next, $func) {
        return function(...$args) use ($next, $func) {
            $args[] = $next;
            return $func(...$args);
        };
    }, function() {
        throw new StackException('No handler was able to return a result.');
    });
}

function stackGroup(array $funcs) {
    $end_next = null;
    array_unshift($funcs, function(...$args) use (&$end_next) {
        return $end_next(...array_slice($args, 0, -1));
    });
    $next = stack($funcs);
    return function(...$args) use ($next, &$end_next) {
        $end_next = end($args);
        return $next(...array_slice($args, 0, -1));
    };
}

/** compose(f, g)(x) = f(g(x)) */
function compose(...$funcs) {
    return pipe(...array_reverse($funcs));
}

/** pipe(f, g)(x) = g(f(x)) */
function pipe(...$funcs) {
    return function($arg) use ($funcs) {
        return array_reduce($funcs, function($acc, $func) {
            return $func($acc);
        }, $arg);
    };
}

function joinPath(array $parts, $sep = DIRECTORY_SEPARATOR) {
    return array_reduce(array_filter($parts), function($acc, $part) use ($sep) {
        if ($acc === null) {
            return rtrim($part, $sep);
        }

        return $acc . $sep . ltrim($part, $sep);
    }, null);
}

function isAbsolutePath($path) {
    return strpos($path, '/') === 0;
}
function isRelativePath($path) {
    return strpos($path, './') === 0 || strpos($path, '../') === 0;
}
function isResourcePath($path) {
    return strpos($path, '://') !== false;
}
function isPath($path) {
    return isAbsolutePath($path) || isRelativePath($path) || isResourcePath($path);
}

/** returns the debug type of an object as string for exception printing */
function debugType($v) {
    if (is_object($v)) {
        return 'object ' . get_class($v);
    }

    return gettype($v);
}

function spliceArrayAtKey(array $array, $key, array $values, $after = true) {
    $new_array = [];
    $spliced = false;
    foreach ($array as $array_key => $val) {
        if ($array_key == $key) {
            $spliced = true;
            if ($after) {
                $new_array[$array_key] = $val;
                $new_array = array_merge($new_array, $values);
            } else {
                $new_array = array_merge($new_array, $values);
                $new_array[$array_key] = $val;
            }
        } else {
            $new_array[$array_key] = $val;
        }
    }

    if (!$spliced) {
        throw new PlatesException('Could not find key ' . $key . ' in array.');
    }

    return $new_array;
}

function cachedFileExists(Psr\SimpleCache\CacheInterface $cache, $ttl = 3600, $file_exists = 'file_exists') {
    return function($path) use ($cache, $ttl, $file_exists) {
        $key = 'League.Plates.file_exists.' . $path;
        $res = $cache->get($key);
        if (!$res) {
            $res = $file_exists($path);
            $cache->set($key, $res, $ttl);
        }
        return $res;
    };
}

/** Invokes the callable if the predicate returns true. Returns the value otherwise. */
function when(callable $predicate, callable $fn) {
    return function($arg) use ($predicate, $fn) {
        return $predicate($arg) ? $fn($arg) : $arg;
    };
}
