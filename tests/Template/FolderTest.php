<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Template\Folder;
use org\bovigo\vfs\vfsStream;

class FolderTest extends \PHPUnit\Framework\TestCase
{
    private $folder;

    protected function setUp(): void
    {
        vfsStream::setup('templates');

        $this->folder = new Folder('folder', vfsStream::url('templates'));
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Folder::class, $this->folder);
    }

    public function testSetAndGetName()
    {
        $this->folder->setName('name');
        $this->assertSame('name', $this->folder->getName());
    }

    public function testSetAndGetPath()
    {
        vfsStream::create(
            array(
                'folder' => array(),
            )
        );

        $this->folder->setPath(vfsStream::url('templates/folder'));
        $this->assertSame(vfsStream::url('templates/folder'), $this->folder->getPath());
    }

    public function testSetInvalidPath()
    {
        // The specified directory path "vfs://does/not/exist" does not exist.
        $this->expectException(\LogicException::class);
        $this->folder->setPath(vfsStream::url('does/not/exist'));
    }

    public function testSetAndGetFallback()
    {
        $this->assertFalse($this->folder->getFallback());
        $this->folder->setFallback(true);
        $this->assertTrue($this->folder->getFallback());
    }
}
