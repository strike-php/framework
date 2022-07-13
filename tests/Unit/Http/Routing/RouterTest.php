<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Http\Routing;

use Bambamboole\Framework\Core\Filesystem\Filesystem;
use Bambamboole\Framework\Http\Routing\Router;
use Bambamboole\Framework\Http\Routing\RouteRegistrar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Bambamboole\Framework\Fixtures\Classes\TestController;
use Tests\Bambamboole\Framework\Fixtures\HasFixtures;

class RouterTest extends TestCase
{
    use HasFixtures;

    public function testItCanLoadUnCachedRoutes(): void
    {
        $router = new Router(
            new RouteRegistrar(),
            new Filesystem(),
            $this->getRoutingFixturePath('routes.php'),
            $this->getBootstrapCacheFixturesPath('compiled-routes.php'),
            false,
        );

        $router->loadRoutes();

        $match = $router->match(Request::create('/1', 'POST'));

        self::assertEquals(TestController::class, $match->getHandler());
        self::assertArrayHasKey('id', $match->getParams());
        self::assertEquals('1', $match->getParams('id'));
    }

    public function testItCanLoadCachedRoutes(): void
    {
        $router = new Router(
            new RouteRegistrar(),
            new Filesystem(),
            $this->getRoutingFixturePath('routes.php'),
            $this->getBootstrapCacheFixturesPath('compiled-routes.php'),
            true,
        );

        self::assertFileDoesNotExist($this->getBootstrapCacheFixturesPath('compiled-routes.php'));
        $router->loadRoutes();

        $match = $router->match(Request::create('/1', 'POST'));

        self::assertEquals(TestController::class, $match->getHandler());
        self::assertFileExists($this->getBootstrapCacheFixturesPath('compiled-routes.php'));
    }

    public function testItLoadTheCompiledRoutesOnlyOnce(): void
    {
        self::assertFileExists($this->getBootstrapCacheFixturesPath('prepared-compiled-routes.php'));
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->expects(self::once())
            ->method('exists')
            ->with($this->getBootstrapCacheFixturesPath('prepared-compiled-routes.php'))
            ->willReturn(true);
        $filesystem
            ->expects(self::never())
            ->method('put');
        $router = new Router(
            new RouteRegistrar(),
            $filesystem,
            $this->getRoutingFixturePath('routes.php'),
            $this->getBootstrapCacheFixturesPath('prepared-compiled-routes.php'),
            true,
        );

        $router->loadRoutes();
    }

    protected function tearDown(): void
    {
        @\unlink($this->getBootstrapCacheFixturesPath('compiled-routes.php'));
    }
}
