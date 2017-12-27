<?php

namespace League\Plates\Extension\Path;

use League\Plates;

function resolvePathCompose(callable $resolve_path) {
    return function(Plates\Template $template) use ($resolve_path) {
        return $template->with('path', $resolve_path(ResolvePathArgs::fromTemplate($template, $resolve_path)));
    };
}

/** appends an extension to the name */

function extResolvePath($ext = 'phtml') {
    $full_ext = '.' . $ext;
    $ext_len = strlen($full_ext);
    return function(ResolvePathArgs $args, $next) use ($full_ext, $ext_len) {
        // ext is already there, just skip
        if (strrpos($args->path, $full_ext) === strlen($args->path) - $ext_len) {
            return $next($args);
        }

        return $next($args->withPath($args->path . $full_ext));
    };
}

function prefixResolvePath(array $prefixes, $file_exists = 'file_exists') {
    return function(ResolvePathArgs $args, $next) use ($prefixes, $file_exists) {
        if (!$prefixes) {
            return $next($args);
        }

        foreach ($prefixes as $cur_prefix) {
            $path = strpos($args->path, '/') === 0
                ? $next($args)
                : $next($args->withPath(
                    Plates\Util\joinPath([$cur_prefix, $args->path])
                ));

            // we have a match, let's return
            if ($file_exists($path)) {
                return $path;
            }

            // at this point, we need to try the next prefix, but before we do, let's strip the prefix
            // if there is one since this might a be a relative path
            $stripped_args = null;
            foreach ($prefixes as $prefix) {
                if (strpos($path, $prefix) === 0) {
                    $stripped_args = $args->withPath(substr($path, strlen($prefix))); // remove the prefix
                    break;
                }
            }

            // could not strip the prefix, so there's not point in continuing on
            if (!$stripped_args) {
                return $path;
            }

            $args = $stripped_args;
        }

        // at this point, none of the paths resolved into a valid path, let's just return the last one
        return $path;
    };
}

/** Figures out the path based off of the parent templates current path */
function relativeResolvePath() {
    return function(ResolvePathArgs $args, $next) {
        $is_relative = (
            strpos($args->path, './') === 0
            || strpos($args->path, '../') === 0
        ) && $args->template->parent;

        if (!$is_relative) {
            return $next($args); // nothing to do
        }

        $current_directory = dirname($args->template->parent()->get('path'));
        return $next($args->withPath(
            Plates\Util\joinPath([$current_directory, $args->path])
        ));
    };
}

function idResolvePath() {
    return function(ResolvePathArgs $args, $next) {
        return $args->path;
    };
}
