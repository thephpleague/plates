<?php

namespace League\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Template\Template;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
    public function register(Engine $engine): void;
    public function setTemplate(?Template $template): void;
}
