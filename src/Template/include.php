<?php

namespace League\Plates\Template;

use League\Plates;

function phpInclude() {
    /**
     * @param string    the template path
     * @param array     the template vars
     */
    return function() {
        ob_start();
        extract(func_get_arg(1));
        include func_get_arg(0);
        return ob_get_clean();
    };
}

/** provieds a map of path -> includer */
function mockInclude($mocks) {
    return function($path, array $vars) use ($mocks) {
        if (!isset($mocks[$path])) {
            throw new Plates\Exception\PlatesException('Mock include does not exist for path: ' . $path);
        }

        return $mocks[$path]($path, $vars);
    };
}
