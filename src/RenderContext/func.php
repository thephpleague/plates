<?php

namespace League\Plates\RenderContext;

use League\Plates;

function layoutFunc() {
    return function(FuncArgs $args) {
        list($name, $data) = $args->args;

        $layout = $args->template->fork($name, $data ?: []);
        Plates\Template\setLayout($args->template, $layout);

        return $layout;
    };
}

function sectionFunc() {
    return function(FuncArgs $args) {
        list($name) = $args->args;
        return Plates\Template\getSections($args->template)->get($name);
    };
}

const START_APPEND = 0;
const START_PREPEND = 1;
const START_REPLACE = 2;

/** Starts the output buffering for a section, update of 0 = replace, 1 = append, 2 = prepend */
function startFunc($update = START_REPLACE) {
    return startBufferFunc(function(FuncArgs $args) use ($update) {
        return function($contents) use ($update, $args) {
            $name = $args->args[0];
            $sections = Plates\Template\getSections($args->template);

            if ($update === START_APPEND) {
                $sections->append($name, $contents);
            } else if ($update === START_PREPEND) {
                $sections->prepend($name, $contents);
            } else {
                $sections->add($name, $contents);
            }
        };
    });
}

function startBufferFunc(callable $create_callback) {
    return function(FuncArgs $args) use ($create_callback) {
        $buffer_stack = $args->template->getContextItem('buffer_stack') ?: [];

        ob_start();
        $buffer_stack[] = [ob_get_level(), $create_callback($args)];

        $args->template->context['buffer_stack'] = $buffer_stack;
    };
}

function endFunc() {
    return function(FuncArgs $args) {
        $buffer_stack = $args->template->getContextItem('buffer_stack') ?: [];
        if (!count($buffer_stack)) {
            throw new Plates\Exception\FuncException('Cannot end a section definition because no section has been started.');
        }

        list($ob_level, $callback) = array_pop($buffer_stack);

        if ($ob_level != ob_get_level()) {
            throw new Plates\Exception\FuncException('Output buffering level does not match when section was started.');
        }

        $contents = ob_get_clean();

        $callback($contents);

        $args->template->context['buffer_stack'] = $buffer_stack;
    };
}

function insertFunc($echo = null) {
    $echo = $echo ?: function($v) { echo $v; };

    return function(FuncArgs $args) use ($echo) {
        list($name, $data) = $args->args;
        $child = $args->template->fork($name, $data ?: []);
        $echo($args->render->renderTemplate($child));
    };
}

function escapeFunc($flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8') {
    return function(FuncArgs $args) use ($flags, $encoding) {
        return htmlspecialchars($args->args[0], $flags, $encoding);
    };
}

function assertArgsFunc($num_required, $num_default = 0) {
    return function(FuncArgs $args, $next) use ($num_required, $num_default) {
        if (count($args->args) < $num_required) {
            throw new Plates\Exception\FuncException("Func {$args->func_name} has {$num_required} argument(s).");
        }

        if (count($args->args) >= $num_required + $num_default) {
            return $next($args);
        }

        $args = $args->withArgs(array_merge($args->args, array_fill(
            0,
            $num_required + $num_default - count($args->args),
            null
        )));

        return $next($args);
    };
}

/** Creates aliases for certain functions */
function aliasNameFunc(array $aliases) {
    return function(FuncArgs $args, $next) use ($aliases) {
        if (!isset($aliases[$args->func_name])) {
            return $next($args);
        }

        while (isset($aliases[$args->func_name])) {
            $args = $args->withName($aliases[$args->func_name]);
        }

        return $next($args);
    };
}

/** Allows splitting of the handlers from the args name */
function splitByNameFunc(array $handlers) {
    return function(FuncArgs $args, $next) use ($handlers) {
        $name = $args->func_name;
        if (isset($handlers[$name])) {
            $handler = Plates\Util\stackGroup($handlers[$name]);
            return $handler($args, $next);
        }

        return $next($args);
    };
}

function platesFunc() {
    $template_args = assertArgsFunc(1, 1);
    $one_arg = assertArgsFunc(1);
    return Plates\Util\stackGroup([
        aliasNameFunc([
            'e' => 'escape',
            '__invoke' => 'escape',
            'stop' => 'end',
        ]),
        splitByNameFunc([
            'layout' => [$template_args, layoutFunc()],
            'section' => [$one_arg, sectionFunc()],
            'insert' => [$template_args, insertFunc()],
            'escape' => [$one_arg, escapeFunc()],
            'start' => [$one_arg, startFunc()],
            'push' => [$one_arg, startFunc(START_APPEND)],
            'unshift' => [$one_arg, startFunc(START_PREPEND)],
            'end' => [endFunc()]
        ])
    ]);
}
