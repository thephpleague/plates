<?php

namespace League\Plates\Template;

use League\Plates;

function absoluteResolveName($file_exists = 'file_exists') {
    return function(ResolveNameArgs $args, $next) use ($file_exists) {
        if ($file_exists($args->name)) {
            return $args->name;
        }

        return $next($args);
    };
}

/** appends an extension to the name */
function extResolveName($ext = 'phtml') {
    $full_ext = '.' . $ext;
    $ext_len = strlen($full_ext);
    return function(ResolveNameArgs $args, $next) use ($full_ext, $ext_len) {
        // ext is already there, just skip
        if (strrpos($args->name, $full_ext) === strlen($args->name) - $ext_len) {
            return $next($args);
        }

        return $next($args->withName($args->name . $full_ext));
    };
}

function prefixResolveName($prefix) {
    return function(ResolveNameArgs $args, $next) use ($prefix) {
        if (strpos($args->name, '/') === 0) {
            return $next($args);
        }

        return $next($args->withName(
            Plates\Util\joinPath([$prefix, $args->name])
        ));
    };
}

/** If the template context stores a current directory and  */
function relativeResolveName() {
    return function(ResolveNameArgs $args, $next) {
        $is_relative = (
            strpos($args->name, './') === 0
            || strpos($args->name, '../') === 0
        ) && isset($args->context['current_directory']);

        if (!$is_relative) {
            return $next($args); // nothing to do
        }

        return $next($args->withName(
            Plates\Util\joinPath([$args->context['current_directory'], $args->name])
        ));
    };
}

/** Just return the name as is to be rendered. Expects at this point for the name to be fully built. */
function idResolveName() {
    return function(ResolveNameArgs $args) {
        return $args->name;
    };
}

function platesResolveName(array $config = []) {
    return Plates\Util\stackGroup(array_filter([
        absoluteResolveName(),
        relativeResolveName(),
        isset($config['ext']) ? extResolveName($config['ext']) : extResolveName(),
        isset($config['base_dir']) ? prefixResolveName($config['base_dir']) : null,
        idResolveName(),
    ]));
}
