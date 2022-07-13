<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Core\Container;

use Bambamboole\Framework\Core\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testItCanBindAnAbstraction(): void
    {
        $container = new Container();
        $container->bind(TestClassInterface::class, SimpleTestClass::class);

        self::assertTrue($container->has(TestClassInterface::class));
    }

    public function testItCanResolveSimpleClasses(): void
    {
        $container = new Container();
        $container->bind(TestClassInterface::class, SimpleTestClass::class);

        $instance = $container->get(TestClassInterface::class);

        self::assertInstanceOf(SimpleTestClass::class, $instance);
    }

    public function testItCanResolveBoundInstances(): void
    {
        $container = new Container();
        $container->instance(TestClassInterface::class, new SimpleTestClass());

        $instance = $container->get(TestClassInterface::class);

        self::assertInstanceOf(SimpleTestClass::class, $instance);
    }

    public function testItCanRegisterSingletons(): void
    {
        $container = new Container();
        $container->instance(TestClassInterface::class, new SimpleTestClass(), true);

        $instance = $container->get(TestClassInterface::class);

        self::assertInstanceOf(SimpleTestClass::class, $instance);
        self::assertTrue($container->resolved(TestClassInterface::class));
    }

    public function testItCanRecursivelyResolveDependencies()
    {
        $container = new Container();
        $container->bind(TestClassInterface::class, ComplexTestClass::class);

        $instance = $container->get(TestClassInterface::class);

        self::assertInstanceOf(ComplexTestClass::class, $instance);
    }
}

interface TestClassInterface
{
}

class SimpleTestClass implements TestClassInterface
{
}

class ComplexTestClass implements TestClassInterface
{
    public function __construct(SimpleTestClass $testClass)
    {
    }
}
