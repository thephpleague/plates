<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Engine;
use League\Plates\Template\Name;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    private $engine;

    protected function setUp(): void
    {
        vfsStream::setup('templates');
        vfsStream::create(
            array(
                'template.php' => '',
                'fallback.php' => '',
                'folder' => array(
                    'template.php' => '',
                ),
            )
        );

        $this->engine = new Engine(vfsStream::url('templates'));
        $this->engine->addFolder('folder', vfsStream::url('templates/folder'), true);
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Template\Name', new Name($this->engine, 'template'));
    }

    public function testGetEngine()
    {
        $name = new Name($this->engine, 'template');

        $this->assertInstanceOf('League\Plates\Engine', $name->getEngine());
    }

    public function testGetName()
    {
        $name = new Name($this->engine, 'template');

        $this->assertSame('template', $name->getName());
    }

    public function testGetFolder()
    {
        $name = new Name($this->engine, 'folder::template');
        $folder = $name->getFolder();

        $this->assertInstanceOf('League\Plates\Template\Folder', $folder);
        $this->assertSame('folder', $name->getFolder()->getName());
    }

    public function testGetFile()
    {
        $name = new Name($this->engine, 'template');

        $this->assertSame('template.php', $name->getFile());
    }

    public function testGetPath()
    {
        $name = new Name($this->engine, 'template');

        $this->assertSame('vfs://templates/template.php', $name->getPath());
    }

    public function testGetPathWithFolder()
    {
        $name = new Name($this->engine, 'folder::template');

        $this->assertSame('vfs://templates/folder/template.php', $name->getPath());
    }

    public function testGetPathWithFolderFallback()
    {
        $name = new Name($this->engine, 'folder::fallback');

        $this->assertSame('vfs://templates/fallback.php', $name->getPath());
    }

    public function testTemplateExists()
    {
        $name = new Name($this->engine, 'template');

        $this->assertTrue($name->doesPathExist());
    }

    public function testTemplateDoesNotExist()
    {
        $name = new Name($this->engine, 'missing');

        $this->assertFalse($name->doesPathExist());
    }

    public function testParse()
    {
        $name = new Name($this->engine, 'template');

        $this->assertSame('template', $name->getName());
        $this->assertNull($name->getFolder());
        $this->assertSame('template.php', $name->getFile());
    }

    public function testParseWithNoDefaultDirectory()
    {
        // The default directory has not been defined.
        $this->expectException(\LogicException::class);

        $this->engine->setDirectory(null);
        $name = new Name($this->engine, 'template');
        $name->getPath();
    }

    public function testParseWithEmptyTemplateName()
    {
        // The template name cannot be empty.
        $this->expectException(\LogicException::class);

        $name = new Name($this->engine, '');
    }

    public function testParseWithFolder()
    {
        $name = new Name($this->engine, 'folder::template');

        $this->assertSame('folder::template', $name->getName());
        $this->assertSame('folder', $name->getFolder()->getName());
        $this->assertSame('template.php', $name->getFile());
    }

    public function testParseWithFolderAndEmptyTemplateName()
    {
        // The template name cannot be empty.
        $this->expectException(\LogicException::class);

        $name = new Name($this->engine, 'folder::');
    }

    public function testParseWithInvalidName()
    {
        // Do not use the folder namespace separator "::" more than once.
        $this->expectException(\LogicException::class);

        $name = new Name($this->engine, 'folder::template::wrong');
    }

    public function testParseWithNoFileExtension()
    {
        $this->engine->setFileExtension(null);

        $name = new Name($this->engine, 'template.php');

        $this->assertSame('template.php', $name->getName());
        $this->assertNull($name->getFolder());
        $this->assertSame('template.php', $name->getFile());
    }
}
