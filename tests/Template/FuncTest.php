<?php

declare(strict_types=1);

namespace League\Plates\Tests\Template;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use League\Plates\Template\Func;
use PHPUnit\Framework\TestCase;

class FuncTest extends TestCase
{
    private $function;

    protected function setUp(): void
    {
        $this->function = new Func('uppercase', function ($string) {
            return strtoupper($string);
        });
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Func::class, $this->function);
    }

    public function testSetAndGetName()
    {
        $this->assertInstanceOf(Func::class, $this->function->setName('test'));
        $this->assertSame('test', $this->function->getName());
    }

    public function testSetInvalidName()
    {
        // Not a valid function name.
        $this->expectException(\LogicException::class);
        $this->function->setName('invalid-function-name');
    }

    public function testSetAndGetCallback()
    {
        $this->assertInstanceOf(Func::class, $this->function->setCallback('strtolower'));
        $this->assertSame('strtolower', $this->function->getCallback());
    }

    public function testSetInvalidCallback()
    {
        // Not a valid function callback.
        $this->expectException(\LogicException::class);
        $this->function->setCallback(null);
    }

    public function testFunctionCall()
    {
        $this->assertSame('JONATHAN', $this->function->call(null, array('Jonathan')));
    }

    public function testExtensionFunctionCall()
    {
        $extension = new class() implements ExtensionInterface {
            public function register(Engine $engine)
            {
            }
            public function foo(): string
            {
                return 'bar';
            }
        };
        $this->function->setCallback(array($extension, 'foo'));
        $this->assertSame('bar', $this->function->call(null));
    }
}
