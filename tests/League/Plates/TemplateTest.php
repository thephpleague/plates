<?php

namespace League\Plates;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    private $template;

    public function setUp()
    {
        $this->template = new Template(new Engine(realpath('tests/assets'), 'tpl'));
    }

    public function testCanCreateTemplate()
    {
        $this->assertInstanceOf('League\Plates\Template', $this->template);
    }

    public function testFunctionCall()
    {
        $this->assertTrue($this->template->batch('Jonathan ', 'trim') === 'Jonathan');
    }

    public function testSetLayout()
    {
        $this->assertNull($this->template->layout('test'));
    }

    public function testSetLayoutWithData()
    {
        $this->template->layout('test', array('name' => 'Jonathan'));
        $this->assertTrue($this->template->name === 'Jonathan');
    }

    public function testSetData()
    {
        $this->template->data(array('name' => 'Jonathan'));
        $this->assertTrue(isset($this->template->name));
    }

    public function testSetVariable()
    {
        $this->template->name = 'Jonathan';
        $this->assertTrue($this->template->name === 'Jonathan');
    }

    public function testSection()
    {
        $this->template->start('name');
        echo 'Jonathan';
        $this->template->end();
        $this->assertTrue($this->template->name === 'Jonathan');
    }

    public function testInsert()
    {
        $this->expectOutputString('Jonathan');
        $this->template->insert('basic-template');
    }

    public function testInsertWithData()
    {
        $this->expectOutputString('Jonathan');
        $this->template->insert('template-with-data', array('name' => 'Jonathan'));
    }

    public function testRender()
    {
        $this->assertTrue($this->template->render('basic-template') === 'Jonathan');
    }

    public function testRenderWithLayout()
    {
        $this->assertTrue($this->template->render('template-with-layout') === 'Hello, Jonathan');
    }

    public function testRenderWithData()
    {
        $this->assertTrue($this->template->render('template-with-data', array('name' => 'Jonathan')) === 'Jonathan');
    }
}
