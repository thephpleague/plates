<?php

namespace League\Plates;

class EscapedTemplateTest extends \PHPUnit_Framework_TestCase
{
    private $template;

    public function setUp()
    {
        $this->template = new EscapedTemplate(new Engine(realpath('tests/assets'), 'tpl'));
    }

    public function testSetData()
    {
        $this->template->data(array('name' => 'Woody'));
        $this->assertTrue(isset($this->template->name));
    }

    public function testSetVariable()
    {
        $this->template->name = 'Woody';
        $this->assertTrue($this->template->name === 'Woody');
    }

    public function testVariableIsEscaped()
    {
        $this->template->name = '<script>';
        $this->assertTrue($this->template->name === '&lt;script&gt;');
    }

    public function testGetRawVariable()
    {
        $this->template->name = '<script>';
        $this->assertTrue($this->template->raw('name') === '<script>');
    }
}
