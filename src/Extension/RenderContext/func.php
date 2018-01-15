<?php

namespace League\Plates\Extension\RenderContext;

use League\Plates;
use League\Plates\Exception\FuncException;

function componentFunc($insert = null) {
    $insert = $insert ?: insertFunc();
    return startBufferFunc(function(FuncArgs $args) use ($insert) {
        if ($args->template()->get('component_slot_data') !== null) {
            throw new FuncException('Cannot nest component func calls.');
        }

        $args->template()->with('component_slot_data', []);
        return function($contents) use ($insert, $args) {
            list($name, $data) = $args->args;

            $data = array_merge(
                $data ?: [],
                ['slot' => $contents],
                $args->template()->get('component_slot_data')
            );

            $insert($args->withArgs([$name, $data]));

            $args->template()->with('component_slot_data', null);
        };
    });
}

function slotFunc() {
    return startBufferFunc(function(FuncArgs $args) {
        if ($args->template()->get('component_slot_data') === null) {
            throw new FuncException('Cannot call slot func outside of component definition.');
        }

        return function($contents) use ($args) {
            $slot_data = $args->template()->get('component_slot_data');
            $slot_data[$args->args[0]] = $contents;
            $args->template()->with('component_slot_data', $slot_data);
        };
    });
}

function startBufferFunc(callable $create_callback) {
    return function(FuncArgs $args) use ($create_callback) {
        $buffer_stack = $args->template()->get('buffer_stack') ?: [];

        ob_start();
        $buffer_stack[] = [ob_get_level(), $create_callback($args)];

        $args->template()->with('buffer_stack', $buffer_stack);
    };
}

function endFunc() {
    return function(FuncArgs $args) {
        $buffer_stack = $args->template()->get('buffer_stack') ?: [];
        if (!count($buffer_stack)) {
            throw new FuncException('Cannot end a section definition because no section has been started.');
        }

        list($ob_level, $callback) = array_pop($buffer_stack);

        if ($ob_level != ob_get_level()) {
            throw new FuncException('Output buffering level does not match when section was started.');
        }

        $contents = ob_get_clean();

        $callback($contents);

        $args->template()->with('buffer_stack', $buffer_stack);
    };
}

function insertFunc($echo = null) {
    $echo = $echo ?: Plates\Util\phpEcho();

    return function(FuncArgs $args) use ($echo) {
        list($name, $data) = $args->args;
        $child = $args->template()->fork($name, $data ?: []);
        $echo($args->render->renderTemplate($child));
    };
}

function templateDataFunc() {
    return function(FuncArgs $args) {
        list($data) = $args->args;
        return array_merge($args->template()->data, $data);
    };
}

/** Enables the backwards compatibility with the old extension functions */
function wrapSimpleFunc(callable $func, $enable_bc = false) {
    return function(FuncArgs $args) use ($func, $enable_bc) {
        if ($enable_bc && is_array($func) && isset($func[0]) && $func[0] instanceof Plates\Extension\ExtensionInterface) {
            $func[0]->template = $args->template();
        }

        return $func(...$args->args);
    };
}

function accessTemplatePropFunc($prop) {
    return function(FuncArgs $args) use ($prop) {
        return $args->template()->{$prop};
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
            throw new FuncException("Func {$args->func_name} has {$num_required} argument(s).");
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

function assertTemplateArgsFunc() {
    return assertArgsFunc(1, 2);
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

function notFoundFunc() {
    return function(FuncArgs $args) {
        throw new FuncException('The function ' . $args->func_name . ' does not exist.');
    };
}
