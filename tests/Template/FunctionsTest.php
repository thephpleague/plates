<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Template\Functions;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    private $functions;

    protected function setUp(): void
    {
        $this->functions = new Functions();
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Functions::class, $this->functions);
    }

    public function testAddAndGetFunction()
    {
        $this->assertInstanceOf(Functions::class, $this->functions->add('upper', 'strtoupper'));
        $this->assertSame('strtoupper', $this->functions->get('upper')->getCallback());
    }

    public function testAddFunctionConflict()
    {
        // The template function name "upper" is already registered.
        $this->expectException(\LogicException::class);
        $this->functions->add('upper', 'strtoupper');
        $this->functions->add('upper', 'strtoupper');
    }

    public function testGetNonExistentFunction()
    {
        // The template function "foo" was not found.
        $this->expectException(\LogicException::class);
        $this->functions->get('foo');
    }

    public function testRemoveFunction()
    {
        $this->functions->add('upper', 'strtoupper');
        $this->assertTrue($this->functions->exists('upper'));
        $this->functions->remove('upper');
        $this->assertFalse($this->functions->exists('upper'));
    }

    public function testRemoveNonExistentFunction()
    {
        // The template function "foo" was not found.
        $this->expectException(\LogicException::class);
        $this->functions->remove('foo');
    }

    public function testFunctionExists()
    {
        $this->assertFalse($this->functions->exists('upper'));
        $this->functions->add('upper', 'strtoupper');
        $this->assertTrue($this->functions->exists('upper'));
    }
}
