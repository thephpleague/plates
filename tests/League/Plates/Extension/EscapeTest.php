<?php

namespace League\Plates\Extension;

class EscapeTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Escape;
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Escape', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testStringEscaping()
    {
        $this->assertTrue($this->extension->escapeString('') === '');
        $this->assertTrue($this->extension->escapeString('Test') === 'Test');
        $this->assertTrue($this->extension->escapeString('\'') === '&#039;');
    }
}
