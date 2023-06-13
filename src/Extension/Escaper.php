<?php

namespace League\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Template\Template;

use function htmlspecialchars;

use const ENT_HTML401; // 0
use const ENT_HTML5; // 48
use const ENT_XHTML; // 32
use const ENT_XML1; // 16

/**
 * Extension that adds default escaper template functions.
 *
 * @method string e($string, string $functions = null, int $ent_doctype = null, string $encoding = null, bool $double_encode = true)
 */
class Escaper implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * The htmlspecialchars target doctype. One of ENT_HTML401, ENT_HTML5,
     * ENT_XHTML, ENT_XML1. Defaults to ENT_HTML401 (0)
     *
     * @var int
     */
    protected $ent_doctype = ENT_HTML401;

    /**
     * The htmlspecialchars encoding charset
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     * valid ENT_* doctype constants map
     */
    const ENT_DOCTYPES = [
        ENT_HTML401 => ENT_HTML401,
        ENT_HTML5   => ENT_HTML5,
        ENT_XHTML   => ENT_XHTML,
        ENT_XML1    => ENT_XML1,
    ];

    public function __construct(int $ent_doctype = null, string $encoding = null)
    {
        if ($ent_doctype !== null) {
            $ent_doctype = $this->parseEntDoctype($ent_doctype);
            if (isset($ent_doctype)) {
                 $this->ent_doctype = $ent_doctype;
            }
        }

        if (isset($encoding)) {
            $this->encoding = $encoding;
        }
    }

    /**
     * Register extension functions.
     *
     * @param Engine $engine
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('escape', array($this, 'escape'));
        $engine->registerFunction('e', array($this, 'escape'));
    }

    /**
     * Escape string
     *
     * @param string $string
     * @param string $functions Function pipe string expression
     * @param int|null $ent_doctype Use an alternative ENT_(HTML5, XHTML, XML1) doctype constant
     * @param string|null $encoding Use an alternative charset
     * @param bool $double_encode
     * @return string
     */
    public function escape(
        $string,
        string $functions = null,
        int $ent_doctype = null,
        string $encoding = null,
        bool $double_encode = true
    ): string {

        if ($functions && $this->template instanceof Template) {
            $string = $this->template->batch($string, $functions);
        }

        // Stop further processing of empty values
        if ($string === null || $string === '') {
            return '';
        }

        if ($ent_doctype !== null) {
            $ent_doctype = $this->parseEntDoctype($ent_doctype);
        }

        return htmlspecialchars(
            (string)$string, // Perform type-casting
            ($ent_doctype ?? $this->ent_doctype) | ENT_QUOTES | ENT_SUBSTITUTE,
            $encoding ?? $this->encoding,
            $double_encode
        );
    }

    /**
     * Return an ENT_* doctype constant integer value or null if the input is
     * not a valid constant
     *
     * @param int $ent_doctype
     * @return int|null
     */
    protected function parseEntDoctype(int $ent_doctype)
    {
        return self::ENT_DOCTYPES[$ent_doctype] ?? null;
    }
}
