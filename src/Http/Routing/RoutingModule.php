<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

use Strike\Framework\Core\Application;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Core\ModuleInterface;

class RoutingModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app,
        private readonly ConfigInterface $config,
    ) {
    }

    public function register(): void
    {
        $this->app->bind(RouteRegistrar::class, fn () => new RouteRegistrar(), true);
        $this->app->bind(
            Router::class,
            fn (ContainerInterface $container) => new Router(
                $container->get(RouteRegistrar::class),
                $container->get(Filesystem::class),
                $this->getRoutesPath(),
                $this->getCachedRoutesPath(),
            ),
            true,
        );
    }

    public function load(): void
    {
        /** @var Router $router */
        $router = $this->app->get(Router::class);
        $router->loadRoutes();
    }

    private function getRoutesPath(): string
    {
        return $this->config->get(
            'http.routes.path',
            $this->app->getBasePath('routes.php'),
        );
    }

    private function getCachedRoutesPath(): string
    {
        return $this->config->get(
            'http.routes.cache_path',
            $this->app->getBasePath('bootstrap/cache/routes.php'),
        );
    }
}
