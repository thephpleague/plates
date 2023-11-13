<?php

namespace League\Plates\Template;

use Exception;
use League\Plates\Engine;
use League\Plates\Template\Name;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

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

        $this->mergePropertyToData();
        $this->autowireDataToTemplateClass();

        $vars = $this->getVarToAutowireDisplayMethod();
        $this->templateClass->display(...$vars);
    }

    protected function mergePropertyToData(): void
    {
        $properties = (new ReflectionClass($this->templateClass))->getProperties(ReflectionProperty::IS_PUBLIC);

        $dataToImport = [];
        foreach ($properties as $property) {
            $propertyValue = $property->getValue($this->templateClass);
            if ($propertyValue === $property->getDefaultValue() || $propertyValue === null)
                continue;

            $dataToImport[$property->getName()] = $propertyValue;
        }

        if ($dataToImport !== []) {
            $this->data($dataToImport);
        }

    }

    protected function getVarToAutowireDisplayMethod(): array
    {
        $displayReflection = new ReflectionMethod($this->templateClass, 'display');

        $parameters = $displayReflection->getParameters();

        // Extract the parameter names
        $parametersToAutowire = [];
        foreach ($parameters as $parameter) {
            if (in_array($parameter->getType()->getName(), [TemplateClass::class, Template::class], true)) {
                $parametersToAutowire[$parameter->getName()] = $this;

                continue;
            }

            if ($parameter->getName() === 'f') {
                $parametersToAutowire['f'] = [$this, 'fetch'];

                continue;
            }

            $parametersToAutowire[$parameter->getName()] = $this->data()[$parameter->getName()] ?? $parameter->getDefaultValue() ?? null;
        }

        return $parametersToAutowire;

    }

    protected function autowireDataToTemplateClass()
    {
        $properties = get_object_vars($this->templateClass);
        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyValue !== null) {
                continue;
            }
            if ($propertyName === 'template') {
                $this->templateClass->template = $this;
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
        return get_class($this->templateClass);
    }
}
