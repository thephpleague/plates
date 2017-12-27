<?php

namespace League\Plates;

/** Converts a template into a string. The top-level RenderTemplate is passed in so that any render template
    can implement recursive rendering. */
interface RenderTemplate
{
    /** @return string */
    public function renderTemplate(Template $template, RenderTemplate $rt = null);
}
