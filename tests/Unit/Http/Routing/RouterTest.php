<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http\Routing;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Http\Routing\Router;
use Strike\Framework\Http\Routing\RouteRegistrar;
use Symfony\Component\HttpFoundation\Request;
use Tests\Strike\Framework\Fixtures\Application\App\Http\TestController;
use Tests\Strike\Framework\Fixtures\HasFixtures;

class RouterTest extends TestCase
{
    use HasFixtures;

    public function testItCanLoadUnCachedRoutes(): void
    {
        $router = new Router(
            new RouteRegistrar(),
            new Filesystem(),
            $this->getTestingApplicationBasePath('etc/routes.php'),
            $this->getCacheFixturesPath('compiled-routes.php'),
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
            $this->getTestingApplicationBasePath('etc/routes.php'),
            $this->getCacheFixturesPath('compiled-routes.php'),
            true,
        );

        self::assertFileDoesNotExist($this->getCacheFixturesPath('compiled-routes.php'));
        $router->loadRoutes();

        $match = $router->match(Request::create('/1', 'POST'));

        self::assertEquals(TestController::class, $match->getHandler());
        self::assertFileExists($this->getCacheFixturesPath('compiled-routes.php'));
    }

    public function testItLoadTheCompiledRoutesOnlyOnce(): void
    {
        self::assertFileExists($this->getCacheFixturesPath('prepared-compiled-routes.php'));
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->expects(self::once())
            ->method('exists')
            ->with($this->getCacheFixturesPath('prepared-compiled-routes.php'))
            ->willReturn(true);
        $filesystem
            ->expects(self::never())
            ->method('put');
        $router = new Router(
            new RouteRegistrar(),
            $filesystem,
            $this->getTestingApplicationBasePath('etc/routes.php'),
            $this->getCacheFixturesPath('prepared-compiled-routes.php'),
            true,
        );

        $router->loadRoutes();
    }

    protected function tearDown(): void
    {
        @\unlink($this->getCacheFixturesPath('compiled-routes.php'));
    }
}
