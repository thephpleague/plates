<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Template\FileExtension;
use PHPUnit\Framework\TestCase;

class FileExtensionTest extends TestCase
{
    private $fileExtension;

    protected function setUp(): void
    {
        $this->fileExtension = new FileExtension();
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Template\FileExtension', $this->fileExtension);
    }

    public function testSetFileExtension()
    {
        $this->assertInstanceOf('League\Plates\Template\FileExtension', $this->fileExtension->set('tpl'));
        $this->assertSame('tpl', $this->fileExtension->get());
    }

    public function testSetNullFileExtension()
    {
        $this->assertInstanceOf('League\Plates\Template\FileExtension', $this->fileExtension->set(null));
        $this->assertNull($this->fileExtension->get());
    }

    public function testGetFileExtension()
    {
        $this->assertSame('php', $this->fileExtension->get());
    }
}
