<?php

namespace League\Plates\Extension;

class AssetTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function setUp()
    {
        $this->extension = new Asset(realpath('tests/assets'));
    }

    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Asset', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }

    public function testCachedAssetUrl()
    {
        $this->assertTrue($this->extension->cachedAssetUrl('styles.css') === 'styles.css?v=' . filemtime(realpath('tests/assets/styles.css')));
    }

    public function testCachedAssetUrlUsingFilenameMethod()
    {
        $this->extension = new Asset(realpath('tests/assets'), true);
        $this->assertTrue($this->extension->cachedAssetUrl('styles.css') === 'styles.' . filemtime(realpath('tests/assets/styles.css')) . '.css');
    }

    public function testFileNotFoundException()
    {
        $this->setExpectedException('LogicException');
        $this->extension->cachedAssetUrl('404.css');
    }
}
