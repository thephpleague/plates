<?php

namespace League\Plates\Extension;

class InsertTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Insert;
        $this->extension->engine = new \League\Plates\Engine(realpath('tests/assets'), 'tpl');
        $this->extension->template = new \League\Plates\Template($this->extension->engine);
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Insert', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testInsertTemplate()
    {
        $this->expectOutputString('Jonathan');
        $this->extension->insertTemplate('basic-template');
    }

    public function testInsertTemplateWithData()
    {
        $this->expectOutputString('Jonathan');
        $this->extension->insertTemplate('template-with-data', array('name' => 'Jonathan'));
    }
}
