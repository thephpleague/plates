<?php

declare(strict_types=1);

namespace League\Plates\Tests\Extension;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    protected function setUp(): void
    {
        vfsStream::setup('assets');
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Extension\Asset', new Asset(vfsStream::url('assets')));
        $this->assertInstanceOf('League\Plates\Extension\Asset', new Asset(vfsStream::url('assets'), true));
        $this->assertInstanceOf('League\Plates\Extension\Asset', new Asset(vfsStream::url('assets'), false));
    }

    public function testRegister()
    {
        $engine = new Engine();
        $extension = new Asset(vfsStream::url('assets'));
        $extension->register($engine);
        $this->assertTrue($engine->doesFunctionExist('asset'));
    }

    public function testCachedAssetUrl()
    {
        vfsStream::create(
            array(
                'styles.css' => '',
            )
        );

        $extension = new Asset(vfsStream::url('assets'));
        $this->assertTrue($extension->cachedAssetUrl('styles.css') === 'styles.css?v=' . filemtime(vfsStream::url('assets/styles.css')));
        $this->assertTrue($extension->cachedAssetUrl('/styles.css') === '/styles.css?v=' . filemtime(vfsStream::url('assets/styles.css')));
    }

    public function testCachedAssetUrlInFolder()
    {
        vfsStream::create(
            array(
                'folder' => array(
                    'styles.css' => '',
                ),
            )
        );

        $extension = new Asset(vfsStream::url('assets'));
        $this->assertTrue($extension->cachedAssetUrl('/folder/styles.css') === '/folder/styles.css?v=' . filemtime(vfsStream::url('assets/folder/styles.css')));
    }

    public function testCachedAssetUrlUsingFilenameMethod()
    {
        vfsStream::create(
            array(
                'styles.css' => '',
            )
        );

        $extension = new Asset(vfsStream::url('assets'), true);
        $this->assertTrue($extension->cachedAssetUrl('styles.css') === 'styles.' . filemtime(vfsStream::url('assets/styles.css')) . '.css');
    }

    public function testFileNotFoundException()
    {
        // Unable to locate the asset "styles.css" in the @assets directory.
        $this->expectException(\LogicException::class);

        $extension = new Asset(vfsStream::url('assets'));
        $extension->cachedAssetUrl('styles.css');
    }
}
