<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Template\Directory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    private $directory;

    protected function setUp(): void
    {
        vfsStream::setup('templates');

        $this->directory = new Directory();
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Template\Directory', $this->directory);
    }

    public function testSetDirectory()
    {
        $this->assertInstanceOf('League\Plates\Template\Directory', $this->directory->set(vfsStream::url('templates')));
        $this->assertSame($this->directory->get(), vfsStream::url('templates'));
    }

    public function testSetNullDirectory()
    {
        $this->assertInstanceOf('League\Plates\Template\Directory', $this->directory->set(null));
        $this->assertNull($this->directory->get());
    }

    public function testSetInvalidDirectory()
    {
        // The specified path "vfs://does/not/exist" does not exist.
        $this->expectException(\LogicException::class);
        $this->directory->set(vfsStream::url('does/not/exist'));
    }

    public function testGetDirectory()
    {
        $this->assertNull($this->directory->get());
    }
}
