<?php

namespace League\Plates;

use org\bovigo\vfs\vfsStream;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    private $engine;

    public function setUp()
    {
        $this->engine = new Engine;

        vfsStream::setup('templates');
        vfsStream::create(
            array(
                'home.php' => '',
                'emails' => array(
                    'welcome.php' => ''
                )
            )
        );
    }

    public function testCanCreateEngine()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine);
    }

    public function testSetValidDirectory()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setDirectory(null));
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setDirectory(vfsStream::url('templates')));
    }

    public function testSetInvalidDirectory()
    {
        $this->setExpectedException('LogicException');
        $this->engine->setDirectory(vfsStream::url('does/not/exist'));
    }

    public function testSetInvalidDirectoryFileType()
    {
        $this->setExpectedException('LogicException');
        $this->engine->setDirectory(array());
    }

    public function testSetValidFileExtension()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setFileExtension('tpl'));
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setFileExtension(null));
    }

    public function testSetInvalidFileExtension()
    {
        $this->setExpectedException('LogicException');
        $this->engine->setFileExtension(array());
    }

    public function testAddValidFolder()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->addFolder('emails', vfsStream::url('templates/emails')));
    }

    public function testAddFolderWithInvalidNamespace()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder(array(), vfsStream::url('templates'));
    }

    public function testAddFolderWithInvalidDirectory()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder('namespace', vfsStream::url('does/not/exist'));
    }

    public function testAddFolderWithInvalidDirectoryFileType()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder('namespace', array());
    }

    public function testAddFolderDirectoryWithNamespaceConflict()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder('namespace', vfsStream::url('templates'));
        $this->engine->addFolder('namespace', vfsStream::url('templates'));
    }

    public function testLoadValidExtension()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->loadExtension($extension));
    }

    public function testLoadExtensionWithInvalidFunctionsFileType()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(null)
            ->mock();
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithEmptyFunctionsArray()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array())
            ->mock();
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithInvalidFunctionName()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array(null => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithInvalidMethodName()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => null))
            ->mock();
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionsWithFunctionNameConflicts()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
        $this->engine->loadExtension($extension);
    }

    public function testGetFunctionWithInvalidFunction()
    {
        $this->setExpectedException('LogicException');
        $this->engine->getFunction(null);
    }

    public function testGetExtensionWithMissingFunction()
    {
        $this->setExpectedException('LogicException');
        $this->engine->getFunction('function');
    }

    public function testGetFunctionWithValidFunction()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
        $this->assertInternalType('array', $this->engine->getFunction('function'));
    }

    public function testFunctionExistsReturnsTrue()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
        $this->assertTrue($this->engine->functionExists('function'));
    }

    public function testMethodExistsReturnsFalse()
    {
        $this->assertFalse($this->engine->functionExists('function'));
    }

    public function testPathExistsWithValidPath()
    {
        $this->engine->setDirectory(vfsStream::url('templates'));
        $this->assertTrue($this->engine->pathExists('home'));
    }

    public function testPathExistsWithInvalidPath()
    {
        $this->engine->setDirectory(vfsStream::url('templates'));
        $this->assertFalse($this->engine->pathExists('non-existent-template'));
    }

    public function testResolvePathWithInvalidPath()
    {
        foreach (array('', 'a::b::c', '::b', 'a::', array()) as $var) {
            try {
                $this->engine->resolvePath($var);
                $this->fail('No exception thrown for invalid path "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
                // Test passed
            }
        }
    }

    public function testResolvePathWithValidTemplate()
    {
        $this->engine->setDirectory(vfsStream::url('templates'));
        $this->assertInternalType('string', $this->engine->resolvePath('home'));
    }

    public function testResolvePathWithInvalidTemplate()
    {
        $this->setExpectedException('LogicException');
        $this->engine->resolvePath('template_that_does_not_exist');
    }

    public function testResolvePathWithValidFolderAndValidTemplate()
    {
        $this->engine->addFolder('emails', vfsStream::url('templates/emails'));
        $this->assertInternalType('string', $this->engine->resolvePath('emails::welcome'));
    }

    public function testResolvePathWithInvalidFolder()
    {
        $this->setExpectedException('LogicException');
        $this->engine->resolvePath('folder_that_does_not_exist::any_template');
    }

    public function testResolvePathWithInvalidFolderTemplate()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder('emails', vfsStream::url('templates/emails'));
        $this->engine->resolvePath('emails::template_that_does_not_exist');
    }

    public function testMakeTemplate()
    {
        $this->assertInstanceOf('League\Plates\Template', $this->engine->makeTemplate());
    }
}
