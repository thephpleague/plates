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
                ),
                'hello' => array(
                    'world.php' => 'Hello world'
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
        $this->setExpectedException('LogicException', 'The specified directory "vfs://does/not/exist" does not exist.');
        $this->engine->setDirectory(vfsStream::url('does/not/exist'));
    }

    public function testSetInvalidDirectoryFileType()
    {
        $this->setExpectedException('LogicException', 'The directory must be a string or null, array given.');
        $this->engine->setDirectory(array());
    }

    public function testSetValidFileExtension()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setFileExtension('tpl'));
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->setFileExtension(null));
    }

    public function testSetInvalidFileExtension()
    {
        $this->setExpectedException('LogicException', 'The file extension must be a string or null, array given.');
        $this->engine->setFileExtension(array());
    }

    public function testAddValidFolder()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->addFolder('emails', vfsStream::url('templates/emails')));
    }

    public function testAddFolderWithInvalidNamespace()
    {
        $this->setExpectedException('LogicException', 'The namespace must be a string, array given.');
        $this->engine->addFolder(array(), vfsStream::url('templates'));
    }

    public function testAddFolderWithInvalidDirectory()
    {
        $this->setExpectedException('LogicException', 'The specified directory "vfs://does/not/exist" does not exist.');
        $this->engine->addFolder('namespace', vfsStream::url('does/not/exist'));
    }

    public function testAddFolderWithInvalidDirectoryFileType()
    {
        $this->setExpectedException('LogicException', 'The directory must be a string, array given.');
        $this->engine->addFolder('namespace', array());
    }

    public function testAddFolderDirectoryWithNamespaceConflict()
    {
        $this->setExpectedException('LogicException', 'The folder namespace "namespace" is already being used.');
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
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(null)
            ->mock();
        $this->setExpectedException('LogicException', 'The "' . get_class($extension) . '" getFunctions method must return an array, NULL given.');
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithEmptyFunctionsArray()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array())
            ->mock();
        $this->setExpectedException('LogicException', 'The extension "' . get_class($extension) . '" has no functions defined.');
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithInvalidFunctionName()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array(null => 'method'))
            ->mock();
        $this->setExpectedException('LogicException', 'The function "" is not a valid function name in the "' . get_class($extension) . '" extension.');
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithInvalidMethodName()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => null))
            ->mock();
        $this->setExpectedException('LogicException', 'The method "" is not a valid method name in the "' . get_class($extension) . '" extension.');
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionsWithFunctionNameConflicts()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->setExpectedException('LogicException', 'The function "function" already exists and cannot be used by the "' . get_class($extension) . '" extension.');
        $this->engine->loadExtension($extension);
        $this->engine->loadExtension($extension);
    }


    public function testUnloadExtensionWithInvalidClass()
    {
        $this->setExpectedException('LogicException', 'Unable to unload extension "This\Class\Name\Will\Never\Exist", class name not found.');
        $this->engine->unloadExtension('This\Class\Name\Will\Never\Exist');
    }

    public function testUnloadExtensionWithValidClass()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->unloadExtension(get_class($extension)));
    }

    public function testUnloadExtensionWithInvalidFunction()
    {
        $this->setExpectedException('LogicException', 'Unable to unload extension function, no extensions with the function "this_function_name_will_never_exist" were found.');
        $this->engine->unloadExtensionFunction('this_function_name_will_never_exist');
    }

    public function testUnloadExtensionWithValidFunction()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtension($extension);
        $this->assertInstanceOf('League\Plates\Engine', $this->engine->unloadExtensionFunction('function'));
    }

    public function testGetFunctionWithInvalidFunction()
    {
        $this->setExpectedException('LogicException', 'Not a valid extension function name.');
        $this->engine->getFunction(null);
    }

    public function testGetExtensionWithMissingFunction()
    {
        $this->setExpectedException('LogicException', 'No extensions with the function "function" were found.');
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

    public function testResolvePathWithNoDefaultDirectorySet()
    {
        $this->setExpectedException('LogicException', 'The default directory has not been defined.');
        $this->engine->resolvePath('template_that_does_not_exist');
    }

    public function testResolvePathWithInvalidTemplate()
    {
        $this->engine->setDirectory(vfsStream::url('templates'));
        $this->setExpectedException('LogicException', 'The specified template "template_that_does_not_exist" could not be found at "vfs://templates/template_that_does_not_exist.php".');
        $this->engine->resolvePath('template_that_does_not_exist');
    }

    public function testResolvePathWithValidFolderAndValidTemplate()
    {
        $this->engine->addFolder('emails', vfsStream::url('templates/emails'));
        $this->assertInternalType('string', $this->engine->resolvePath('emails::welcome'));
    }

    public function testResolvePathWithInvalidFolder()
    {
        $this->setExpectedException('LogicException', 'The folder "folder_that_does_not_exist" does not exist.');
        $this->engine->resolvePath('folder_that_does_not_exist::any_template');
    }

    public function testResolvePathWithInvalidFolderTemplate()
    {
        $this->setExpectedException('LogicException', 'The specified template "emails::template_that_does_not_exist" could not be found at "vfs://templates/emails/template_that_does_not_exist.php');
        $this->engine->addFolder('emails', vfsStream::url('templates/emails'));
        $this->engine->resolvePath('emails::template_that_does_not_exist');
    }

    public function testMakeTemplate()
    {
        $this->assertInstanceOf('League\Plates\Template', $this->engine->makeTemplate());
    }

    public function testAddFolders()
    {
        $this->engine->addFolders(array(
            'emails' => vfsStream::url('templates/emails'),
            'hello' => vfsStream::url('templates/hello')
        ));
        $this->assertInternalType('string', $this->engine->resolvePath('emails::welcome'));
        $this->assertInternalType('string', $this->engine->resolvePath('hello::world'));
    }

    public function testLoadExtensions()
    {
        $extension = \Mockery::mock('League\Plates\Extension\ExtensionInterface')
            ->shouldReceive('getFunctions')
            ->andReturn(array('function' => 'method'))
            ->mock();
        $this->engine->loadExtensions(array(
            $extension
        ));
        $function = $this->engine->getFunction('function');
        $this->assertEquals($extension, $function[0]);
    }
}
