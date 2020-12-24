<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Template\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    private $template_data;

    protected function setUp(): void
    {
        $this->template_data = new Data();
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Data::class, $this->template_data);
    }

    public function testAddDataToAllTemplates()
    {
        $this->template_data->add(array('name' => 'Jonathan'));
        $data = $this->template_data->get();
        $this->assertSame('Jonathan', $data['name']);
    }

    public function testAddDataToOneTemplate()
    {
        $this->template_data->add(array('name' => 'Jonathan'), 'template');
        $data = $this->template_data->get('template');
        $this->assertSame('Jonathan', $data['name']);
    }

    public function testAddDataToOneTemplateAgain()
    {
        $this->template_data->add(array('firstname' => 'Jonathan'), 'template');
        $this->template_data->add(array('lastname' => 'Reinink'), 'template');
        $data = $this->template_data->get('template');
        $this->assertSame('Reinink', $data['lastname']);
    }

    public function testAddDataToSomeTemplates()
    {
        $this->template_data->add(array('name' => 'Jonathan'), array('template1', 'template2'));
        $data = $this->template_data->get('template1');
        $this->assertSame('Jonathan', $data['name']);
    }

    public function testAddDataWithInvalidTemplateFileType()
    {
        // The templates variable must be null, an array or a string, integer given.
        $this->expectException(\LogicException::class);
        $this->template_data->add(array('name' => 'Jonathan'), 123);
    }
}
