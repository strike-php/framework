<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core;

use Strike\Framework\Core\Application;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\BootstrapperInterface;
use Strike\Framework\Core\Container\Container;
use Strike\Framework\Core\Exception\IncompatibleBootstrapperException;
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

    public function testItProvidesRelevantPaths(): void
    {
        $basePath = '/test';
        $app = new Application($basePath);

        self::assertStringStartsWith($basePath, $app->getConfigPath());
        self::assertStringStartsWith($basePath, $app->getCachedConfigPath());
        self::assertStringStartsWith($basePath, $app->getRoutesPath());
        self::assertStringStartsWith($basePath, $app->getCachedRoutesPath());
        self::assertStringStartsWith($basePath, $app->getDocumentRoot());
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

    public function testItPipesBindCallsToTheContainer(): void
    {
        $container = $this->createMock(Container::class);
        $app = new Application('/test', $container);
        $container
            ->expects(self::once())
            ->method('bind')
            ->with(FinderFactory::class);

        $app->bind(FinderFactory::class, fn () => '');
    }

    public function testItPipesSingletonCallsToTheContainer(): void
    {
        $container = $this->createMock(Container::class);
        $app = new Application('/test', $container);
        $container
            ->expects(self::once())
            ->method('singleton')
            ->with(FinderFactory::class);

        $app->singleton(FinderFactory::class, fn () => '');
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

        $app = new Application('/test', bootstrappers: []);
        $app->instance(RoutingModule::class, $routingModule);
        $app->registerModule(RoutingModule::class);

        $app->boot();
        $app->boot();
    }

    public function testModuleCanBeRegisteredOnlyOnce(): void
    {
        $routingModule = $this->createMock(RoutingModule::class);
        $routingModule
            ->expects(self::once())
            ->method('register');

        $app = new Application('/test', bootstrappers: []);
        $app->instance(RoutingModule::class, $routingModule);

        $app->registerModule(RoutingModule::class);
        $app->registerModule(RoutingModule::class);
    }

    public function testItThrowsAnExceptionIfModuleDoesNotImplementModuleInterface(): void
    {
        $app = new Application('/test', bootstrappers: []);
        self::expectException(IncompatibleModuleException::class);

        $app->registerModule(InvalidTestModule::class);
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
        $app = new Application('/test', bootstrappers: []);
        $app->instance(Testmodule::class, $moduleMock);
        $app->boot();

        $app->registerModule(Testmodule::class);
    }

    public function testItBindsItselfOntoTheContainer(): void
    {
        $app = new Application('/test');

        self::assertSame($app, $app->get(Application::class));
        self::assertSame($app, $app->get(ApplicationInterface::class));
    }

    public function testItCallsBootstrapOnABootstrapperInstance(): void
    {
        $bootstrapper = $this->createMock(BootstrapperInterface::class);
        $app = new Application('/test', bootstrappers: [$bootstrapper]);
        $bootstrapper
            ->expects(self::once())
            ->method('bootstrap')
            ->with($app);

        $app->boot();
    }

    public function testItResolvesTheBootstrapperViaTheContainer(): void
    {
        $bootstrapper = $this->createMock(BootstrapperInterface::class);
        $app = new Application('/test', bootstrappers: ['\\Tests\\BootstrapperFake']);
        $app->instance('\\Tests\\BootstrapperFake', $bootstrapper);
        $bootstrapper
            ->expects(self::once())
            ->method('bootstrap')
            ->with($app);

        $app->boot();
    }

    public function testItThrowsAnExceptionIfTheBootstrapperDoesNotImplementTheRightInterface(): void
    {
        $app = new Application('/test', bootstrappers: ['\\Tests\\BootstrapperFake']);
        $app->instance('\\Tests\\BootstrapperFake', \stdClass::class);
        self::expectException(IncompatibleBootstrapperException::class);

        $app->boot();
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
