<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Engine;
use League\Plates\Template\Template;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    private $template;

    protected function setUp(): void
    {
        vfsStream::setup('templates');

        $engine = new Engine(vfsStream::url('templates'));
        $engine->registerFunction('uppercase', 'strtoupper');

        $this->template = new Template($engine, 'template');
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Template::class, $this->template);
    }

    public function testCanCallFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->uppercase("jonathan") ?>',
            )
        );

        $this->assertSame('JONATHAN', $this->template->render());
    }

    public function testAssignData()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $name ?>',
            )
        );

        $this->template->data(array('name' => 'Jonathan'));
        $this->assertSame('Jonathan', $this->template->render());
    }

    public function testGetData()
    {
        $data = array('name' => 'Jonathan');

        $this->template->data($data);
        $this->assertSame($this->template->data(), $data);
    }

    public function testExists()
    {
        vfsStream::create(
            array(
                'template.php' => '',
            )
        );

        $this->assertTrue($this->template->exists());
    }

    public function testDoesNotExist()
    {
        $this->assertFalse($this->template->exists());
    }

    public function testGetPath()
    {
        $this->assertSame('vfs://templates/template.php', $this->template->path());
    }

    public function testRender()
    {
        vfsStream::create(
            array(
                'template.php' => 'Hello World',
            )
        );

        $this->assertSame('Hello World', $this->template->render());
    }

    public function testRenderViaToStringMagicMethod()
    {
        vfsStream::create(
            array(
                'template.php' => 'Hello World',
            )
        );

        $actual = (string) $this->template;

        $this->assertSame('Hello World', $actual);
    }

    public function testRenderWithData()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $name ?>',
            )
        );

        $this->assertSame('Jonathan', $this->template->render(array('name' => 'Jonathan')));
    }

    public function testRenderDoesNotExist()
    {
        // The template "template" could not be found at "vfs://templates/template.php".
        $this->expectException(\LogicException::class);
        var_dump($this->template->render());
    }

    public function testRenderException()
    {
        // error
        $this->expectException('Exception');
        vfsStream::create(
            array(
                'template.php' => '<?php throw new Exception("error"); ?>',
            )
        );
        var_dump($this->template->render());
    }

    public function testRenderDoesNotLeakVariables() {
        vfsStream::create(
            array(
                'template.php' => '<?=json_encode(get_defined_vars())?>',
            )
        );

        $this->assertSame('[]', $this->template->render());
    }

    public function testLayout()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout") ?>',
                'layout.php' => 'Hello World',
            )
        );

        $this->assertSame('Hello World', $this->template->render());
    }

    public function testSection()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout")?><?php $this->start("test") ?>Hello World<?php $this->stop() ?>',
                'layout.php' => '<?php echo $this->section("test") ?>',
            )
        );

        $this->assertSame('Hello World', $this->template->render());
    }

    public function testReplaceSection()
    {
        vfsStream::create(
            array(
                'template.php' => implode('\n', array(
                    '<?php $this->layout("layout")?><?php $this->start("test") ?>Hello World<?php $this->stop() ?>',
                    '<?php $this->layout("layout")?><?php $this->start("test") ?>See this instead!<?php $this->stop() ?>',
                )),
                'layout.php' => '<?php echo $this->section("test") ?>',
            )
        );

        $this->assertSame('See this instead!', $this->template->render());
    }

    public function testStartSectionWithInvalidName()
    {
        // The section name "content" is reserved.
        $this->expectException(\LogicException::class);

        vfsStream::create(
            array(
                'template.php' => '<?php $this->start("content") ?>',
            )
        );

        $this->template->render();
    }

    public function testNestSectionWithinAnotherSection()
    {
        // You cannot nest sections within other sections.
        $this->expectException(\LogicException::class);

        vfsStream::create(
            array(
                'template.php' => '<?php $this->start("section1") ?><?php $this->start("section2") ?>',
            )
        );

        $this->template->render();
    }

    public function testStopSectionBeforeStarting()
    {
        // You must start a section before you can stop it.
        $this->expectException(\LogicException::class);

        vfsStream::create(
            array(
                'template.php' => '<?php $this->stop() ?>',
            )
        );

        $this->template->render();
    }

    public function testSectionDefaultValue()
    {
        vfsStream::create(array(
            'template.php' => '<?php echo $this->section("test", "Default value") ?>',
        ));

        $this->assertSame('Default value', $this->template->render());
    }

    public function testNullSection()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout") ?>',
                'layout.php' => '<?php if (is_null($this->section("test"))) echo "NULL" ?>',
            )
        );

        $this->assertSame('NULL', $this->template->render());
    }

    public function testPushSection()
    {
        vfsStream::create(
            array(
                'template.php' => implode('\n', array(
                    '<?php $this->layout("layout")?>',
                    '<?php $this->push("scripts") ?><script src="example1.js"></script><?php $this->end() ?>',
                    '<?php $this->push("scripts") ?><script src="example2.js"></script><?php $this->end() ?>',
                )),
                'layout.php' => '<?php echo $this->section("scripts") ?>',
            )
        );

        $this->assertSame(
            '<script src="example1.js"></script><script src="example2.js"></script>',
            $this->template->render()
        );
    }

    public function testPushWithMultipleSections()
    {
        vfsStream::create(
            array(
                'template.php' => implode('\n', array(
                    '<?php $this->layout("layout")?>',
                    '<?php $this->push("scripts") ?><script src="example1.js"></script><?php $this->end() ?>',
                    '<?php $this->start("test") ?>test<?php $this->stop() ?>',
                    '<?php $this->push("scripts") ?><script src="example2.js"></script><?php $this->end() ?>',
                )),
                'layout.php' => implode('\n', array(
                    '<?php echo $this->section("test") ?>',
                    '<?php echo $this->section("scripts") ?>',
                )),
            )
        );

        $this->assertSame(
            'test\n<script src="example1.js"></script><script src="example2.js"></script>',
            $this->template->render()
        );
    }

    public function testFetchFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->fetch("fetched") ?>',
                'fetched.php' => 'Hello World',
            )
        );

        $this->assertSame('Hello World', $this->template->render());
    }

    public function testInsertFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->insert("inserted") ?>',
                'inserted.php' => 'Hello World',
            )
        );

        $this->assertSame('Hello World', $this->template->render());
    }

    public function testBatchFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->batch("Jonathan", "uppercase|strtolower") ?>',
            )
        );

        $this->assertSame('jonathan', $this->template->render());
    }

    public function testBatchFunctionWithInvalidFunction()
    {
        // The batch function could not find the "function_that_does_not_exist" function.
        $this->expectException(\LogicException::class);

        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->batch("Jonathan", "function_that_does_not_exist") ?>',
            )
        );

        $this->template->render();
    }

    public function testEscapeFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->escape("<strong>Jonathan</strong>") ?>',
            )
        );

        $this->assertSame('&lt;strong&gt;Jonathan&lt;/strong&gt;', $this->template->render());
    }

    public function testEscapeFunctionBatch()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->escape("<strong>Jonathan</strong>", "strtoupper|strrev") ?>',
            )
        );

        $this->assertSame('&gt;GNORTS/&lt;NAHTANOJ&gt;GNORTS&lt;', $this->template->render());
    }

    public function testEscapeShortcutFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->e("<strong>Jonathan</strong>") ?>',
            )
        );

        $this->assertSame('&lt;strong&gt;Jonathan&lt;/strong&gt;', $this->template->render());
    }
}
