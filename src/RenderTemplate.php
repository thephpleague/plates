<?php

namespace League\Plates;

/** Converts a template into a string */
interface RenderTemplate
{
    /** @return string */
    public function renderTemplate(Template $template);
}
