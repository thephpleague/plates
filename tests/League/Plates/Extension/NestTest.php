<?php

namespace League\Plates\Extension;

class NestTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Nest;
        $this->extension->engine = new \League\Plates\Engine(realpath('tests/assets'), 'tpl');
        $this->extension->template = new \League\Plates\Template($this->extension->engine);
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Nest', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testGetRenderedTemplate()
    {
        $this->assertTrue($this->extension->getRenderedTemplate('basic-template') === 'Jonathan');
    }

    public function testGetRenderedTemplateWithData()
    {
        $this->assertTrue($this->extension->getRenderedTemplate('template-with-data', array('name' => 'Jonathan')) === 'Jonathan');
    }

    public function testInsertRenderedTemplate()
    {
        $this->expectOutputString('Jonathan');
        $this->extension->insertRenderedTemplate('basic-template');
    }

    public function testInsertRenderedTemplateWithData()
    {
        $this->expectOutputString('Jonathan');
        $this->extension->insertRenderedTemplate('template-with-data', array('name' => 'Jonathan'));
    }
}
