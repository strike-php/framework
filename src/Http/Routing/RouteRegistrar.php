<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class RouteRegistrar
{
    /** @var Route[]  */
    private array $routes;

    public function addRoute(HttpMethod $method, string $path, string $controller): Route
    {
        $this->routes[] = $route = new Route(
            $method,
            $path,
            $controller,
        );

        return $route;
    }

    public function get(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::GET, $path, $controller);
    }

    public function post(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::POST, $path, $controller);
    }

    public function getCollection(): RouteCollection
    {
        $collection = new RouteCollection();
        foreach ($this->routes as $i => $route) {
            $symfonyRoute = $this->createSymfonyRoute($route);
            $symfonyRoute->setMethods($route->getHttpMethod()->value);
            $symfonyRoute->setDefault('_controller', $route->getController());
            $collection->add($route->getName(), $symfonyRoute);
        }

        return $collection;
    }

    private function createSymfonyRoute(Route $route): SymfonyRoute
    {
        return new SymfonyRoute($route->getPath());
    }
}
