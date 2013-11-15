<?php

namespace League\Plates\Extension;

class BatchTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Batch;
        $this->extension->engine = new \League\Plates\Engine();
        $this->extension->template = new \League\Plates\Template($this->extension->engine);
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Batch', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testRunBatch()
    {
        $this->assertTrue($this->extension->runBatch('Sweet ', 'trim') === 'Sweet');
        $this->assertTrue($this->extension->runBatch('Jonathan', 'strtoupper|strtolower') === 'jonathan');
        $this->assertTrue($this->extension->runBatch('Jonathan', 'e|strtolower') === 'jonathan');
    }

    public function testRunBatchException()
    {
        $this->setExpectedException('LogicException');
        $this->assertTrue($this->extension->runBatch('Jonathan', 'somefunctionthatwillneverexist'));
    }
}
