<?php

namespace League\Plates\Template;

use Exception;
use League\Plates\Engine;
use League\Plates\Template\Name;
use Throwable;

/**
 * Container which holds template data and provides access to template functions.
 */
class TemplateClass extends Template
{
    public function __construct(
        Engine $engine,
        protected TemplateClassInterface $templateClass
    ) {
        $this->engine = $engine;
        $name = $templateClass::class;

        $this->data($this->engine->getData($name)); // needed for addData, too much magic, deprecate it ?!
    }

    protected function display() {

        $this->autowireDataToTemplateClass();
        $this->templateClass->display($this);
    }

    protected function autowireDataToTemplateClass()
    {
        $properties = get_object_vars($this->templateClass);
        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyValue !== null) {
                continue;
            }

            $this->templateClass->$propertyName = $this->data[$propertyName] ?? null;
        }
    }

    /** Disable useless public/protected parent method and property */

    /**
     * @var Name Useless here
     */
    protected $name;

    public function exists(): bool
    {
        return true;
    }

    public function path(): string
    {
        return '';
    }
}
