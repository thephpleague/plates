<?php

namespace League\Plates\Extension;

class URITest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new URI('/green/red/blue');
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\URI', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testGetUrl()
    {
        $this->assertTrue($this->extension->runUri() === '/green/red/blue');
    }

    public function testGetSpecifiedSegment()
    {
        $this->assertTrue($this->extension->runUri(1) === 'green');
        $this->assertTrue($this->extension->runUri(2) === 'red');
        $this->assertTrue($this->extension->runUri(3) === 'blue');
    }

    public function testSegmentMatch()
    {
        $this->assertTrue($this->extension->runUri(1, 'green'));
        $this->assertFalse($this->extension->runUri(1, 'red'));
    }

    public function testSegmentMatchSuccessResponse()
    {
        $this->assertTrue($this->extension->runUri(1, 'green', 'success') === 'success');
    }

    public function testSegmentMatchFailureResponse()
    {
        $this->assertTrue($this->extension->runUri(1, 'red', 'success', 'fail') === 'fail');
    }

    public function testRegexMatch()
    {
        $this->assertTrue($this->extension->runUri('/[a-z]+/red/blue'));
    }

    public function testRegexMatchSuccessResponse()
    {
        $this->assertTrue($this->extension->runUri('/[a-z]+/red/blue', 'success') === 'success');
    }

    public function testRegexMatchFailureResponse()
    {
        $this->assertTrue($this->extension->runUri('/[0-9]+/red/blue', 'success', 'fail') === 'fail');
    }
}
