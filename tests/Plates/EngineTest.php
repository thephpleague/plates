<?php

namespace Plates;

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
        $this->assertInstanceOf('Plates\Engine', $this->engine);
    }

    public function testSetValidDirectory()
    {
        $this->assertNull($this->engine->setDirectory(null));
        $this->assertNull($this->engine->setDirectory(vfsStream::url('templates')));
    }

    public function testSetInvalidDirectory()
    {
        foreach (array(vfsStream::url('does/not/exist'), array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->setDirectory($var);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testSetValidFileExtension()
    {
        $this->assertNull($this->engine->setFileExtension('tpl'));
    }

    public function testSetInvalidFileExtension()
    {
        foreach (array(null, array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->setFileExtension($var);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testAddValidFolder()
    {
        $this->assertNull($this->engine->addFolder('emails', vfsStream::url('templates/emails')));
    }

    public function testAddFolderWithInvalidNamespace()
    {
        foreach (array(null, array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->addFolder($var, vfsStream::url('templates'));
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testAddFolderWithInvalidDirectory()
    {
        foreach (array(vfsStream::url('does/not/exist'), array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->addFolder('namespace', $var);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testAddFolderDirectoryWithNamespaceConflict()
    {
        $this->setExpectedException('LogicException');
        $this->engine->addFolder('namespace', vfsStream::url('templates'));
        $this->engine->addFolder('namespace', vfsStream::url('templates'));
    }

    public function testLoadValidExtension()
    {
        $extension = \Mockery::mock('\Plates\Extension\Base');
        $extension->methods = array('someMethod');
        $this->assertNull($this->engine->loadExtension($extension));
    }

    public function testLoadExtensionWithoutMethodsParamaterDefined()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('\Plates\Extension\Base');
        $this->engine->loadExtension($extension);
    }

    public function testLoadExtensionWithInvalidMethodsParamater()
    {
        foreach (array(array(), 'string', true, 1, new \StdClass) as $var) {
            try {
                $extension = \Mockery::mock('\Plates\Extension\Base');
                $extension->methods = $var;
                $this->engine->loadExtension($extension);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testLoadExtensionWithInvalidMethodsParamaterValues()
    {
        foreach (array('', array(), true, 1, new \StdClass) as $var) {
            try {
                $extension = \Mockery::mock('\Plates\Extension\Base');
                $extension->methods = array($var);
                $this->engine->loadExtension($extension);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testLoadExtensionsWithMethodsParamaterConflicts()
    {
        $this->setExpectedException('LogicException');
        $extension = \Mockery::mock('\Plates\Extension\Base');
        $extension->methods = array('someMethod', 'someMethod');
        $this->engine->loadExtension($extension);
    }

    public function testGetExtensionWithValidMethod()
    {
        $extension = \Mockery::mock('\Plates\Extension\Base');
        $extension->methods = array('someMethod');
        $this->engine->loadExtension($extension);
        $this->assertInstanceOf('\Plates\Extension\Base', $this->engine->getExtension('someMethod'));
    }

    public function testGetExtensionWithInvalidMethod()
    {
        foreach (array('methodThatDoesNotExist', null, array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->getExtension($var);
                $this->fail('No exception thrown for invalid variable type "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
            }
        }
    }

    public function testResolvePathWithInvalidPath()
    {
        foreach (array('', 'a::b::c', '::b', 'a::', null, array(), true, 1, new \StdClass) as $var) {
            try {
                $this->engine->resolvePath($var);
                $this->fail('No exception thrown for invalid path "' . gettype($var) . '".');
            } catch (\LogicException $expected) {
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
}
