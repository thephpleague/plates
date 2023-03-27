<?php

namespace League\Plates\Template;

/**
 * Defines the object format of the data target 
 * to be rendered in a Template's Section.
 */
class TemplateSection
{
    /**
     * Content history.
     *
     * @var array
     */
    protected $mutations = [];

    /**
     * Section name.
     *
     * @var string
     */
    protected $name;

    /**
     * @param string  $name
     * @param ?string $content
     * @param ?int    $mode
     */
    public function __construct(string $name, $content = '', $mode = Template::SECTION_MODE_REWRITE)
    {
        $this->name = $name;
        if (!empty($content)) {
            $this->add($content, $mode);
        }
    }

    /**
     * @param  string $content
     * @param  int    $mode
     * @return void
     */
    public function add(string $content, int $mode)
    {
        $this->mutations[] = new TemplateSectionMutation($mode, $content);
        usort($this->mutations, function ($m1, $m2) {
            if ($m1->mode === Template::SECTION_MODE_REWRITE && $m2->mode !== Template::SECTION_MODE_REWRITE) {
                return -1;
            } else if ($m1->mode !== Template::SECTION_MODE_REWRITE && $m2->mode === Template::SECTION_MODE_REWRITE) {
                return 1;
            }
            return 0;
        });
    }

    public function merge($other)
    {
        if ($other->name !== $this->name) {
            throw new \InvalidArgumentException("Only same section may be merged");
        }

        $this->mutations = array_merge($this->mutations, $other->mutations);
        usort($this->mutations, function ($m1, $m2) {
            if ($m1->mode === Template::SECTION_MODE_REWRITE && $m2->mode !== Template::SECTION_MODE_REWRITE) {
                return -1;
            } else if ($m1->mode !== Template::SECTION_MODE_REWRITE && $m2->mode === Template::SECTION_MODE_REWRITE) {
                return 1;
            }
            return 0;
        });

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): string
    {
        $content = '';
        foreach ($this->mutations as $mutation) {
            // if this template doesn't have that section, so we just add it.
            if (empty($content)) {
                $content = $mutation->content;
                continue;
            }

            // otherwise we need to consider the incoming section mode
            if ($mutation->mode === Template::SECTION_MODE_REWRITE) {
                $content = $mutation->content;
            } else if ($mutation->mode === Template::SECTION_MODE_APPEND) {
                $content = $content . $mutation->content;
            } else if ($mutation->mode === Template::SECTION_MODE_PREPEND) {
                $content = $mutation->content . $content;
            }
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
