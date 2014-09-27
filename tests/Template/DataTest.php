<?php

namespace League\Plates\Template;

class DataTest extends \PHPUnit_Framework_TestCase
{
    private $data;

    public function setUp()
    {
        $this->data = new Data;
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf('League\Plates\Template\Data', $this->data);
    }

    public function testAddData()
    {
        $this->data->add(array('name' => 'Jonathan'));
        $data = $this->data->get();
        $this->assertEquals($data['name'], 'Jonathan');
    }

    public function testAddDataWithTemplate()
    {
        $this->data->add(array('name' => 'Jonathan'), 'template');
        $data = $this->data->get('template');
        $this->assertEquals($data['name'], 'Jonathan');
    }

    public function testAddDataWithTemplates()
    {
        $this->data->add(array('name' => 'Jonathan'), array('template1', 'template2'));
        $data = $this->data->get('template1');
        $this->assertEquals($data['name'], 'Jonathan');
    }

    public function testAddDataWithInvalidTemplateFileType()
    {
        $this->setExpectedException('LogicException', 'The templates variable must be null, an array or a string, integer given.');
        $this->data->add(array('name' => 'Jonathan'), 123);
    }
}
