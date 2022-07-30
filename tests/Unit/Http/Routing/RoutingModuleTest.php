<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http\Routing;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Http\Routing\Router;
use Strike\Framework\Http\Routing\RouteRegistrar;
use Strike\Framework\Http\Routing\RoutingModule;
use PHPUnit\Framework\TestCase;

class RoutingModuleTest extends TestCase
{
    public function testItBindsTheRouterCorrectly(): void
    {
        $app = $this->createMock(ApplicationInterface::class);

        $app
            ->method('getRoutesPath')
            ->willReturn('/routes');
        $app
            ->method('getCachedRoutesPath')
            ->willReturn('/routes/cached');
        $app
            ->expects(self::exactly(2))
            ->method('singleton')
            ->withConsecutive(
                [RouteRegistrar::class, self::callback(fn ($fn) => $fn() instanceof RouteRegistrar)],
                [Router::class, self::callback(function ($fn) {
                    $container = $this->createMock(ContainerInterface::class);
                    $container
                        ->expects(self::exactly(2))
                        ->method('get')
                        ->withConsecutive([RouteRegistrar::class], [Filesystem::class])
                        ->willReturnOnConsecutiveCalls(new RouteRegistrar(), new Filesystem());
                    $router = $fn($container);

                    return $router instanceof Router
                        && $router->getRoutesPath() === '/routes'
                        && $router->getCompiledRoutesPath() === '/routes/cached'
                        && $router->isCacheEnabled() === true;
                })],
            );

        $module = new RoutingModule($app);

        $module->register();
    }
}
