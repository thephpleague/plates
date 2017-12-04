<?php

namespace League\Plates\Template;

use League\Plates;

function phpInclude() {
    /**
     * @param string    the template path
     * @param array     the template vars
     */
    $inc = function() {
        ob_start();
        extract(func_get_arg(1));
        include func_get_arg(0);
        return ob_get_clean();
    };

    return function(...$args) use ($inc) {
        $cur_level = ob_get_level();
        try {
            return $inc(...$args);
        } catch (\Exception $e) {}
          catch (\Throwable $e) {}

        // clean the ob stack
        while (ob_get_level() > $cur_level) {
            ob_end_clean();
        }

        throw $e;
    };
}

function validatePathInclude($include, $file_exists = 'file_exists') {
    return function($path, array $vars) use ($include, $file_exists) {
        if (!$file_exists($path)) {
            throw new Plates\Exception\PlatesException('Template path ' . $path . ' does not exist.');
        }

        return $include($path, $vars);
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
