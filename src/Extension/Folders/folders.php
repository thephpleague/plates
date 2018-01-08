<?php

namespace League\Plates\Extension\Folders;

use League\Plates;
use League\Plates\Extension\Path\ResolvePathArgs;

function foldersResolvePath(array $folders, $sep = '::', $file_exists = 'file_exists') {
    return function(ResolvePathArgs $args, $next) use ($folders, $sep, $file_exists) {
        if (strpos($args->path, $sep) === false) {
            return $next($args);
        }

        list($folder, $name) = explode($sep, $args->path);
        if (!isset($folders[$folder])) {
            return $next($args);
        }
        $folder_struct = $folders[$folder];

        foreach ($folder_struct['prefixes'] as $prefix) {
            $path = $next($args->withPath(
                Plates\Util\joinPath([$prefix, $name])
            ));

            // no need to check if file exists if we only have prefix
            if (count($folder_struct['prefixes']) == 1 || $file_exists($path)) {
                return $path;
            }
        }

        // none of the paths matched, just return what we have.
        return $path;
    };
}
