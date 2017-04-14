<?php

namespace League\Plates\Extension;

class TranslateTest extends \PHPUnit_Framework_TestCase {
    
    private $testable;
    
    protected function setUp()
    {
        $translation = array(
            "foo" => "Bar",
            "The king is dead" => "Long live the king!"
        );
        
        $locales = array('en_GB', 'en_US');
        
        $this->extension = new Translate(
            $translation,
            'en_GB',
            $locales
        );
        $this->extension->engine = new \League\Plates\Engine();
        $this->extension->template = new \League\Plates\Template($this->extension->engine);
        
    }
    
    public function testCanCreateExtension()
    {
        $this->assertInstanceOf('League\Plates\Extension\Translate', $this->extension);
    }

    public function testGetFunctionsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->extension->getFunctions());
    }

    public function testGetFunctionsReturnsAtLeastOneRecord()
    {
        $this->assertTrue(count($this->extension->getFunctions()) > 0);
    }
    
    public function testCurrentLocale()
    {
        $this->assertEquals($this->extension->locale(), 'en_GB');
    }
    
    public function testNeedleWithoutSpaces()
    {
        $this->assertEquals($this->extension->translate('foo'), 'Bar');
    }
    
    public function testNeedleWithSpaces()
    {
        $this->assertSame(
            $this->extension->translate('The king is dead'),
            'Long live the king!'
        );
    }
    
    public function testNonExistingNeedle()
    {
        $this->assertEquals($this->extension->translate("PHP"), 'PHP');
    }
    
    public function testLocales()
    {
        $this->assertEquals(
            $this->extension->availableLocales(),
            array('en_GB', 'en_US')
        );
    }
    
    public function testEmptyObject()
    {
        $extension = new Translate(array());
        $this->assertEquals($extension->translate("foo"), 'foo');
        $this->assertEquals($extension->locale(), null);
        $this->assertEquals($extension->availableLocales(), array());
    }
}
