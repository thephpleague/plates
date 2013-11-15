<?php

namespace League\Plates;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    private $template;

    public function setUp()
    {
        $this->template = new Template(new Engine());
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
}
