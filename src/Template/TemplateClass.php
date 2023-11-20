<?php

namespace League\Plates\Template;

use Exception;
use League\Plates\Engine;
use League\Plates\Template\Name;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
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
            if (!$property->isInitialized($this->templateClass)) // $property->isReadOnly() &&
                continue;

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

            if ($parameter->getType() instanceof ReflectionNamedType // avoid union or intersection type
                && in_array($parameter->getType()->getName(), [TemplateClass::class, Template::class], true)) {
                $parametersToAutowire[$parameter->getName()] = $this;

                continue;
            }

            if ($parameter->getName() === 'f') {
                $parametersToAutowire['f'] = $this->fetch(...);

                continue;
            }


            if ($parameter->getName() === 'e') {
                $parametersToAutowire['e'] = $this->escape(...);

                continue;
            }

            $parametersToAutowire[$parameter->getName()] = $this->data()[$parameter->getName()] ?? $parameter->getDefaultValue() ?? null;
        }

        return $parametersToAutowire;

    }

    protected function autowireDataToTemplateClass()
    {
        $properties = (new ReflectionClass($this->templateClass))->getProperties();

        foreach ($properties as $property) {
            if ($property->isInitialized($this->templateClass)) {
                continue;
            }

            if ($property->getName() === 'template') {
                $this->templateClass->template = $this;
                continue;
            }

            if (isset($this->data[$property->getName()])) {
                $this->templateClass->{$property->getName()} = $this->data[$property->getName()];
            }
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
