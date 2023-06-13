<?php

declare(strict_types=1);

namespace League\Plates\Tests\Extension;

use League\Plates\Engine;
use League\Plates\Extension\Escaper;
use League\Plates\Template\Template;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

use const ENT_HTML401;
use const ENT_HTML5;
use const ENT_XHTML;
use const ENT_XML1;

class EscaperTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Escaper::class, new Escaper());
        $this->assertInstanceOf(Escaper::class, new Escaper(ENT_HTML5));
        $this->assertInstanceOf(Escaper::class, new Escaper(ENT_XHTML));
        $this->assertInstanceOf(Escaper::class, new Escaper(ENT_XML1));
        $this->assertInstanceOf(Escaper::class, new Escaper(ENT_HTML401, 'ISO-8859-15'));
    }

    public function testThatEscaperIsAutoRegisteredByDefault()
    {
        $engine = new Engine();
        $this->assertTrue($engine->doesFunctionExist('escape'));
        $this->assertTrue($engine->doesFunctionExist('e'));
    }

    public function testThatEscaperAutoRegistrationCanBeBypassed()
    {
        $engine = new Engine(null, 'php', false);
        $this->assertFalse($engine->doesFunctionExist('escape'));
        $this->assertFalse($engine->doesFunctionExist('e'));
    }

    public function testDefaultHtml401EscapeFunction()
    {
        $string   = '<&>"\'';
        $expected = '&lt;&amp;&gt;&quot;&#039;';

        $escaper = new Escaper();
        $this->assertSame($expected, $escaper->escape($string));
    }

    public function testHtml5EscapeFunction()
    {
        $string   = '<&>"\'';
        $expected = '&lt;&amp;&gt;&quot;&apos;';

        $escaper = new Escaper();
        $this->assertSame($expected, $escaper->escape($string, null, ENT_HTML5));

        $escaper = new Escaper(ENT_HTML5);
        $this->assertSame($expected, $escaper->escape($string));
    }

    public function testXhtmlEscapeFunction()
    {
        $string   = '<&>"\'';
        $expected = '&lt;&amp;&gt;&quot;&apos;';

        $escaper = new Escaper();
        $this->assertSame($expected, $escaper->escape($string, null, ENT_XHTML));

        $escaper = new Escaper(ENT_XHTML);
        $this->assertSame($expected, $escaper->escape($string));
    }

    public function testXml1EscapeFunction()
    {
        $string   = '<&>"\'';
        $expected = '&lt;&amp;&gt;&quot;&apos;';

        $escaper = new Escaper();
        $this->assertSame($expected, $escaper->escape($string, null, ENT_XML1));

        $escaper = new Escaper(ENT_XML1);
        $this->assertSame($expected, $escaper->escape($string));
    }

    public function testThatDoubleEncodeIsEnabledByDefault()
    {
        $string   = '&lt;&amp;&gt;&quot;&apos;';
        $expected = '&amp;lt;&amp;amp;&amp;gt;&amp;quot;&amp;apos;';

        $escaper = new Escaper(ENT_HTML5);
        $this->assertSame($expected, $escaper->escape($string));
    }

    public function testThatDoubleEncodeCanBeDisabled()
    {
        $escaper = new Escaper();

        $string = '&lt;&amp;&gt;&quot;&apos;';

        $expected = '&lt;&amp;&gt;&quot;&amp;apos;';
        $this->assertSame($expected, $escaper->escape($string, null, null, null, false));

        $expected = '&lt;&amp;&gt;&quot;&apos;';
        $this->assertSame($expected, $escaper->escape($string, null, ENT_HTML5, null, false));
        $this->assertSame($expected, $escaper->escape($string, null, ENT_XHTML, null, false));
        $this->assertSame($expected, $escaper->escape($string, null, ENT_XML1, null, false));
    }

    public function testThatBatchFunctionsAreCalledFromWithinTemplate()
    {
        vfsStream::setup('templates');

        $engine = new Engine(vfsStream::url('templates'));
        $engine->registerFunction('tr', 'trim');
        $engine->registerFunction('uc', 'strtoupper');

        $template = new Template($engine, 'template');

        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->batch(" abc ", "tr|uc") ?>',
            )
        );

        $this->assertSame('ABC', $template->render());
    }

    public function testThatBatchFunctionsAreSkippedIfOutsideTemplate()
    {
        $escaper = new Escaper(ENT_HTML5);

        $engine = new Engine(null, 'php', false);
        $engine->loadExtension($escaper);

        $engine->registerFunction('tr', 'trim');
        $engine->registerFunction('uc', 'strtoupper');

        $this->assertSame('abc', $escaper->escape('abc', 'tr|uc'));
    }
}
