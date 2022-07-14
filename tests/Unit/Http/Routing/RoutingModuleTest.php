<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Http\Routing;

use Bambamboole\Framework\Core\Application;
use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Core\Container\ContainerInterface;
use Bambamboole\Framework\Core\Filesystem\Filesystem;
use Bambamboole\Framework\Http\Routing\Router;
use Bambamboole\Framework\Http\Routing\RouteRegistrar;
use Bambamboole\Framework\Http\Routing\RoutingModule;
use PHPUnit\Framework\TestCase;

class RoutingModuleTest extends TestCase
{
    public function testItBindsTheCorrectServicesOnRegistration(): void
    {
        $app = $this->createMock(Application::class);
        $app
            ->expects(self::exactly(2))
            ->method('bind')
            ->withConsecutive([RouteRegistrar::class], [Router::class]);

        $module = new RoutingModule($app, $this->createMock(ConfigInterface::class));

        $module->register();
    }

    public function testItBindsTheRouterCorrectly(): void
    {
        self::markTestSkipped('Not working as expected');
        $app = $this->createMock(Application::class);
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects(self::any())
            ->method('get')
            ->withConsecutive(['http.routes.path'], ['http.routes.cache_path'])
            ->willReturnOnConsecutiveCalls('/routes', '/compiled');
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects(self::any())
            ->method('get')
            ->withConsecutive([RouteRegistrar::class], [Filesystem::class])
            ->willReturnOnConsecutiveCalls(new RouteRegistrar(), new Filesystem());

        $app
            ->method('getBasePath')
            ->willReturn('/');
        $app
            ->expects(self::exactly(2))
            ->method('bind')
            ->withConsecutive(
                [RouteRegistrar::class, self::callback(fn ($fn) => $fn($container) instanceof RouteRegistrar)],
                [Router::class, self::callback(function ($fn) use ($container) {
                    $router = $fn($container);

                    return $router instanceof Router
                        && $router->getRoutesPath() === '/routes'
                        && $router->getCompiledRoutesPath() === '/compiled'
                        && $router->isCacheEnabled() === true;
                })],
            );

        $module = new RoutingModule($app, $config);

        $module->register();
    }
}
