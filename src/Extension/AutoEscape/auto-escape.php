<?php

namespace League\Plates\Extension\AutoEscape;

function autoEscapeComposer(callable $escape) {
    return function($template) use ($escape) {
        return $template->withData(array_map(function($v) use ($escape) {
            return EscapedProxy::create($v, $escape);
        }, $template->data));
    };
}
