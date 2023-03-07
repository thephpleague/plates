<?php

namespace League\Plates\Template;

/**
 * Defines the object format of the data target 
 * to be rendered in a Template's Section.
 */
class TemplateSection
{
    /**
     * Section mode.
     *
     * @var int
     */
    protected $mode;

    /**
     * Section content.
     *
     * @var string
     */
    protected $content;

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
        $this->content = $content;
        $this->mode = $mode;
    }

    /**
     * @param  string $content
     * @param  int    $mode
     * @return void
     */
    public function add(string $content, int $mode)
    {
        $this->mode = $mode;

        // if this template doesn't have that section, so we just add it.
        if (empty($this->content)) {
            $this->content = $content;
            return;
        }

        // otherwise we need to consider the incoming section mode
        if ($mode === Template::SECTION_MODE_REWRITE) {
            $this->content = $content;
            return;
        }

        if ($mode === Template::SECTION_MODE_APPEND) {
            $this->content = $this->content . $content;
            return;
        }

        if ($mode === Template::SECTION_MODE_PREPEND) {
            $this->content = $content . $this->content;
        }
    }

    /**
     * @return string|null
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }
}
