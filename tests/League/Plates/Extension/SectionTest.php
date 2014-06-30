<?php

namespace League\Plates\Extension;

class SectionTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Section;
        $this->extension->engine = new \League\Plates\Engine(realpath('tests/assets'), 'tpl');
        $this->extension->template = new \League\Plates\Template($this->extension->engine);
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Section', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testSection()
    {
        $this->extension->startSection('name');
        echo 'Jonathan';
        $this->extension->endSection();
        $this->assertTrue($this->extension->getSection('name') === 'Jonathan');
    }
}
