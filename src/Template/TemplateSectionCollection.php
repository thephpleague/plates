<?php

namespace League\Plates\Template;

use ArrayAccess;
use InvalidArgumentException;

/**
 * Collection of TemplateSections that can be used to fill Template's Sections.
 * Here is defined a common API for merging Template Section data.
 * NOTE: currently this is not iterable, so do not try this in a foreach.
 */
class TemplateSectionCollection implements ArrayAccess
{
    /**
     * @var array<string, TemplateSection>
     */
    private $sections = array();

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function has(string $offset): bool
    {
        return array_key_exists($offset, $this->sections);
    }

    /**
     * @param string $offset
     *
     * @return TemplateSection
     */
    public function get(string $offset)
    {
        return $this->sections[$offset];
    }

    /**
     * @param string               $offset
     * @param TemplateSection|null $value
     *
     * @return void
     */
    public function set(string $offset, $value)
    {
        if (!is_string($offset)) {
            throw new InvalidArgumentException('The desired section offset must be a string.');
        }
        if (!($value instanceof TemplateSection) && $value != null) {
            throw new InvalidArgumentException('The section set must be of type TemplateSection.');
        }
        $this->sections[$offset] = $value;
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (!is_string($offset)) {
            throw new InvalidArgumentException('The desired section offset must be a string.');
        }
        return $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return string
     */
    public function offsetGet($offset): string
    {
        if (!is_string($offset)) {
            throw new InvalidArgumentException('The desired section offset must be a string.');
        }
        return $this->get($offset)->getContent();
    }

    /**
     * @param string $offset
     * @param string $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (!is_string($offset)) {
            throw new InvalidArgumentException('The desired section offset must be a string.');
        }
        $this->add($offset, $value, Template::SECTION_MODE_REWRITE);
    }

    /**
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (!is_string($offset)) {
            throw new InvalidArgumentException('The desired section offset must be a string.');
        }
        $this->sections[$offset] = null;
        unset($this->sections[$offset]);
    }

    /**
     * Pushes the given section content to the sections array.
     * You can use the $mode to merge the content to an existing
     * content if that section content already exists.
     *
     * @param string $name
     * @param string $content
     * @param ?int   $mode
     *
     * @return void
     */
    public function add(string $name, string $content, $mode = Template::SECTION_MODE_APPEND) 
    {
        if ($this->has($name)) {
            $this->sections[$name]->add($content, $mode);
            return;
        }
        $this->sections[$name] = new TemplateSection($name, $content, $mode);
    }

    /**
     * Merges another Template Sections collection into 
     * this one taking in consideration the template 
     * section's modes.
     *
     * @return void
     */
    public function merge(TemplateSectionCollection $templateSections)
    {
        foreach ($templateSections->sections as $section) {
            if (isset($this->sections[$section->getName()])) {
                $this->sections[$section->getName()]->merge($section);
            } else {
                $this->sections[$section->getName()] = $section;
            }
        }
    }
}
