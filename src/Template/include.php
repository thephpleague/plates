<?php

namespace League\Plates\Template;

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
