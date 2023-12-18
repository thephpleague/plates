<?php

namespace League\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Template\Template;

abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * @var Template|null Instance of the current Template - if applicable
     */
    public ?Template $template;

    abstract public function register(Engine $engine): void;
}
