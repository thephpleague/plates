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

function getSections(Template $template) {
    if (!isset($template->context['sections'])) {
        setSection($template, new Section());
    }

    return $template->context['sections'];
}

function setSections(Template $template, Sections $sections) {
    $template->context['sections'] = $sections;
    return $template;
}
