<?php

namespace League\Plates\Template;

use League\Plates\Template;

function matchPathExtension(callable $match) {
    return matchAttribute('path', function($path) use ($match) {
        return $match(pathinfo($path, PATHINFO_EXTENSION));
    });
}

function matchName($name) {
    return function(Template $template) use ($name) {
        return $template->get('normalized_name', $template->name) == $name;
    };
}

function matchExtensions(array $extensions) {
    return matchPathExtension(function($ext) use ($extensions) {
        return in_array($ext, $extensions);
    });
}

function matchAttribute($attribute, callable $match) {
    return function(Template $template) use ($attribute, $match) {
        return $match($template->get($attribute));
    };
}

function matchStub($res) {
    return function(Template $template) use ($res) {
        return $res;
    };
}

function matchAny(array $matches) {
    return function(Template $template) use ($matches) {
        foreach ($matches as $match) {
            if ($match($template)) {
                return true;
            }
        }

        return false;
    };
}

function matchAll(array $matches) {
    return function(Template $template) use ($matches) {
        foreach ($matches as $match) {
            if (!$match($template)) {
                return false;
            }
        }

        return true;
    };
}
