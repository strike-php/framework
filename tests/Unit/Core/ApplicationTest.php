<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core;

use Strike\Framework\Core\Application;
use Strike\Framework\Core\Config\Config;
use Strike\Framework\Core\Container\Container;
use Strike\Framework\Core\Exception\IncompatibleModuleException;
use Strike\Framework\Core\Filesystem\FinderFactory;
use Strike\Framework\Core\ModuleInterface;
use Strike\Framework\Http\Routing\RoutingModule;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testGetBasePath(): void
    {
        $basePath = '/test';
        $app = new Application($basePath);

        self::assertEquals($basePath, $app->getBasePath());
        self::assertEquals($basePath . '/foo', $app->getBasePath('foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('//foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('\\foo'));
    }

    public function testItSetsTheCorrectTimezone(): void
    {
        \date_default_timezone_set('Europe/Berlin');

        new Application('/test');

        self::assertEquals('UTC', \date_default_timezone_get());
    }

    public function testDefaultTimezoneCanBeConfiguredViaConfig(): void
    {
        \date_default_timezone_set('UTC');

        new Application(basePath: '/test', config: new Config(['app' => ['timezone' => 'Europe/Berlin']]));

        self::assertEquals('Europe/Berlin', \date_default_timezone_get());
    }

    public function testItPipesGetCallsToTheContainer(): void
    {
        $container = $this->createMock(Container::class);
        $container
            ->expects(self::once())
            ->method('get')
            ->with(Testmodule::class);
        $app = new Application('/test', $container);

        $app->get(Testmodule::class);
    }

    public function testItPipesInstanceCallsToTheContainer(): void
    {
        $finderFactory = new FinderFactory();
        $container = $this->createMock(Container::class);
        $app = new Application('/test', $container);
        $container
            ->expects(self::once())
            ->method('instance')
            ->with(FinderFactory::class, $finderFactory);

        $app->instance(FinderFactory::class, $finderFactory);
    }

    public function testItPipesHasCallsToTheContainer(): void
    {
        $container = $this->createMock(Container::class);
        $app = new Application('/test', $container);
        $container
            ->expects(self::once())
            ->method('has')
            ->with(FinderFactory::class);

        $app->has(FinderFactory::class);
    }

    public function testItPipesContainerCalls(): void
    {
        $container = $this->createMock(Container::class);
        $container
            ->expects(self::once())
            ->method('get')
            ->with(Testmodule::class);
        $app = new Application('/test', $container);

        $app->get(Testmodule::class);
    }

    public function testModuleLoadWillBeCalledOnBootOnlyOnce()
    {
        $routingModule = $this->createMock(RoutingModule::class);
        $routingModule
            ->expects(self::once())
            ->method('load');

        $app = new Application('/test');
        $app->instance(RoutingModule::class, $routingModule);
        $app->register(RoutingModule::class);

        $app->boot();
        $app->boot();
    }

    public function testModuleCanBeRegisteredOnlyOnce(): void
    {
        $routingModule = $this->createMock(RoutingModule::class);
        $routingModule
            ->expects(self::once())
            ->method('register');

        $app = new Application('/test');
        $app->instance(RoutingModule::class, $routingModule);

        $app->register(RoutingModule::class);
        $app->register(RoutingModule::class);
    }

    public function testItThrowsAnExceptionIfModuleDoesNotImplementModuleInterface(): void
    {
        $app = new Application('/test');
        self::expectException(IncompatibleModuleException::class);

        $app->register(InvalidTestModule::class);
    }

    public function testModuleWillBeLoadedOnRegistrationAfterBoot(): void
    {
        $moduleMock = $this->createMock(Testmodule::class);
        $moduleMock
            ->expects(self::once())
            ->method('register');
        $moduleMock
            ->expects(self::once())
            ->method('load');
        $app = new Application('/test');
        $app->instance(Testmodule::class, $moduleMock);
        $app->boot();

        $app->register(Testmodule::class);
    }
}

class Testmodule implements ModuleInterface
{
    public function __construct(Application $app)
    {
    }

    public function register(): void
    {
    }

    public function load(): void
    {
    }
}

class InvalidTestModule
{
}
