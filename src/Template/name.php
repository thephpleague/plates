<?php

namespace League\Plates\Template;

use League\Plates;

/** appends a suffix to the name */
function extResolveName($ext = 'phtml') {
    return function($name, array $context) use ($ext) {
        return [$name . '.' . $ext, $context];
    };
}

function prefixResolveName($prefix) {
    return function($name, array $context) use ($prefix) {
        return [
            Plates\Util\joinPath([$prefix, $name]),
            $context
        ];
    };
}

/** If the template context stores a current directory and  */
function relativeResolveName() {
    return function($name, array $context) {
        if (strpos($path, './') !== 0 || !isset($context['current_directory'])) {
            return $name; // nothing to do
        }

        return [
            Plates\Util\joinPath([$context['current_directory'], substr($path, 2)]),
            $context
        ];
    };
}

/** assumes the template name is in the format of `folder::path`.
    folders */
function folderResolveName(array $folders, $sep = '::') {
    return function($name, array $context) use ($folders, $sep) {
        if (strpos($name, $sep) === false) {
            return [$name, $context];
        }

        list($folder, $path) = explode($sep, $name);
        if (!isset($folders[$folder])) {
            return [$name, $context];
        }

        return [
            Plates\Util\joinPath([$folders[$folder], $path]),
            $context
        ];
    };
}

/** will automatically*/
function defaultFolderResolveName(array $folders, $sep = '::') {
    return function($name, array $context) use ($folders, $sep) {
        // if (strpos($name, $sep))
    };
}
