<?php

namespace League\Plates\Extension\LayoutSections;

use League\Plates;
use League\Plates\Template;

use League\Plates\Extension\RenderContext\FuncArgs;
use function League\Plates\Extension\RenderContext\startBufferFunc;

function sectionsCompose() {
    return function(Template $template) {
        return $template->with('sections', $template->parent ? $template->parent()->get('sections') : new Sections());
    };
}

function layoutFunc() {
    return function(FuncArgs $args) {
        list($name, $data) = $args->args;

        $layout = $args->template()->fork($name, $data ?: []);
        $args->template()->with('layout', $layout->reference);

        return $layout;
    };
}

function sectionFunc() {
    return function(FuncArgs $args) {
        list($name, $else) = $args->args;

        $res = $args->template()->get('sections')->get($name);
        if ($res || !$else) {
            return $res;
        }

        return is_callable($else)
            ? Plates\Util\obWrap($else)
            : (string) $else;
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
            $sections = $args->template()->get('sections');

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
