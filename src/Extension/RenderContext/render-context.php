<?php

namespace League\Plates\Extension\RenderContext;

use Closure;
use League\Plates;

function renderContextCompose(callable $render_context_factory, $var_name) {
    return function(Plates\Template $template) use ($render_context_factory, $var_name) {
        $render_context = $render_context_factory($template->reference);
        return $template->withAddedData([
            $var_name => $render_context,
        ])->with('render_context', $render_context);
    };
}

function renderContextBind() {
    return function(Closure $inc, Template $template) use ($var_name) {
        return $template->get('render_context')
            ? $inc->bindTo($template->get('render_context'))
            : $inc;
    };
}
