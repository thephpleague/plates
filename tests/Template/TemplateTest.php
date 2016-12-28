<?php

namespace League\Plates\Template;

use org\bovigo\vfs\vfsStream;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    private $template;

    public function setUp()
    {
        vfsStream::setup('templates');

        $engine = new \League\Plates\Engine(vfsStream::url('templates'));
        $engine->registerFunction('uppercase', 'strtoupper');

        $this->template = new \League\Plates\Template\Template($engine, 'template');
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Template\Template', $this->template);
    }

    public function testCanCallFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->uppercase("jonathan") ?>',
            )
        );

        $this->assertEquals($this->template->render(), 'JONATHAN');
    }

    public function testAssignData()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $name ?>',
            )
        );

        $this->template->data(array('name' => 'Jonathan'));
        $this->assertEquals($this->template->render(), 'Jonathan');
    }

    public function testGetData()
    {
        $data = array('name' => 'Jonathan');

        $this->template->data($data);
        $this->assertEquals($this->template->data(), $data);
    }

    public function testExists()
    {
        vfsStream::create(
            array(
                'template.php' => '',
            )
        );

        $this->assertEquals($this->template->exists(), true);
    }

    public function testDoesNotExist()
    {
        $this->assertEquals($this->template->exists(), false);
    }

    public function testGetPath()
    {
        $this->assertEquals($this->template->path(), 'vfs://templates/template.php');
    }

    public function testRender()
    {
        vfsStream::create(
            array(
                'template.php' => 'Hello World',
            )
        );

        $this->assertEquals($this->template->render(), 'Hello World');
    }

    public function testRenderViaToStringMagicMethod()
    {
        vfsStream::create(
            array(
                'template.php' => 'Hello World',
            )
        );

        $actual = (string) $this->template;

        $this->assertEquals($actual, 'Hello World');
    }

    public function testRenderWithData()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $name ?>',
            )
        );

        $this->assertEquals($this->template->render(array('name' => 'Jonathan')), 'Jonathan');
    }

    public function testRenderDoesNotExist()
    {
        $this->setExpectedException('LogicException', 'The template "template" could not be found at "vfs://templates/template.php".');
        var_dump($this->template->render());
    }

    public function testRenderException()
    {
        $this->setExpectedException('Exception', 'error');
        vfsStream::create(
            array(
                'template.php' => '<?php throw new Exception("error"); ?>',
            )
        );
        var_dump($this->template->render());
    }

    public function testLayout()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout") ?>',
                'layout.php' => 'Hello World',
            )
        );

        $this->assertEquals($this->template->render(), 'Hello World');
    }

    public function testSection()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout")?><?php $this->start("test") ?>Hello World<?php $this->stop() ?>',
                'layout.php' => '<?php echo $this->section("test") ?>',
            )
        );

        $this->assertEquals($this->template->render(), 'Hello World');
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

        $this->assertEquals($this->template->render(), 'See this instead!');
    }

    public function testStartSectionWithInvalidName()
    {
        $this->setExpectedException('LogicException', 'The section name "content" is reserved.');

        vfsStream::create(
            array(
                'template.php' => '<?php $this->start("content") ?>',
            )
        );

        $this->template->render();
    }

    public function testNestSectionWithinAnotherSection()
    {
        $this->setExpectedException('LogicException', 'You cannot nest sections within other sections.');

        vfsStream::create(
            array(
                'template.php' => '<?php $this->start("section1") ?><?php $this->start("section2") ?>',
            )
        );

        $this->template->render();
    }

    public function testStopSectionBeforeStarting()
    {
        $this->setExpectedException('LogicException', 'You must start a section before you can stop it.');

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

        $this->assertEquals($this->template->render(), 'Default value');
    }

    public function testNullSection()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->layout("layout") ?>',
                'layout.php' => '<?php if (is_null($this->section("test"))) echo "NULL" ?>',
            )
        );

        $this->assertEquals($this->template->render(), 'NULL');
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

        $this->assertEquals($this->template->render(), '<script src="example1.js"></script><script src="example2.js"></script>');
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

        $this->assertEquals($this->template->render(), 'test\n<script src="example1.js"></script><script src="example2.js"></script>');
    }

    public function testFetchFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->fetch("fetched") ?>',
                'fetched.php' => 'Hello World',
            )
        );

        $this->assertEquals($this->template->render(), 'Hello World');
    }

    public function testInsertFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php $this->insert("inserted") ?>',
                'inserted.php' => 'Hello World',
            )
        );

        $this->assertEquals($this->template->render(), 'Hello World');
    }

    public function testBatchFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->batch("Jonathan", "uppercase|strtolower") ?>',
            )
        );

        $this->assertEquals($this->template->render(), 'jonathan');
    }

    public function testBatchFunctionWithInvalidFunction()
    {
        $this->setExpectedException('LogicException', 'The batch function could not find the "function_that_does_not_exist" function.');

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

        $this->assertEquals($this->template->render(), '&lt;strong&gt;Jonathan&lt;/strong&gt;');
    }

    public function testEscapeFunctionBatch()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->escape("<strong>Jonathan</strong>", "strtoupper|strrev") ?>',
            )
        );

        $this->assertEquals($this->template->render(), '&gt;GNORTS/&lt;NAHTANOJ&gt;GNORTS&lt;');
    }

    public function testEscapeShortcutFunction()
    {
        vfsStream::create(
            array(
                'template.php' => '<?php echo $this->e("<strong>Jonathan</strong>") ?>',
            )
        );

        $this->assertEquals($this->template->render(), '&lt;strong&gt;Jonathan&lt;/strong&gt;');
    }
}
