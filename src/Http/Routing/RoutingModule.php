<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Core\ModuleInterface;

class RoutingModule implements ModuleInterface
{
    public function __construct(
        private readonly ApplicationInterface $app,
    ) {
    }

    public function register(): void
    {
        $this->app->singleton(RouteRegistrar::class, fn () => new RouteRegistrar());
        $this->app->singleton(
            Router::class,
            fn (ContainerInterface $container) => new Router(
                $container->get(RouteRegistrar::class),
                $container->get(Filesystem::class),
                $this->app->getRoutesPath(),
                $this->app->getCachedRoutesPath(),
                $container->get(ConfigInterface::class)->get('http.routing.cache', true),
            ),
        );
    }

    public function load(): void
    {
        // Add callback to load routes after the application got
        // booted so that any module can add more routes.
        $this->app->afterBoot($this->loadRoutes(...));
    }

    private function loadRoutes(ApplicationInterface $app): void
    {
        /** @var Router $router */
        $router = $app->get(Router::class);
        $router->loadRoutes();
    }
}
