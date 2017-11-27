<?php

namespace League\Plates\Template;

use League\Plates\Template;
use League\Plates\Content;

function addChild(Template $template, Template $child) {
    if (!isset($template->context['children'])) {
        $template->context['children'] = [];
    }

    $template->context['children'][] = $child;
    return $template;
}

function setLayout(Template $template, Template $layout) {
    $template->context['layout'] = $layout;
    return $template;
}
function getLayout(Template $template) {
    return isset($template->context['layout']) ? $template->context['layout'] : null;
}

function setSection(Template $template, $section_name, Content $content) {
    if (!isset($template->context['sections'])) {
        $template->context['sections'] = [];
    }

    $template->context['sections'][$section_name] = $content;
    return $template;
}

function getSection(Template $template, $section_name) {
    return isset($template->context['sections'][$section_name])
        ? $template->context['sections'][$section_name]
        : null;
}
