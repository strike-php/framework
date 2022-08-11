<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

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

    public function head(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::HEAD, $path, $controller);
    }

    public function put(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::PUT, $path, $controller);
    }

    public function patch(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::PATCH, $path, $controller);
    }

    public function post(string $path, string $controller): Route
    {
        return $this->addRoute(HttpMethod::POST, $path, $controller);
    }

    /** @return Route[] */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getCollection(): RouteCollection
    {
        $collection = new RouteCollection();
        foreach ($this->routes as $i => $route) {
            $collection->add($route->getName(), $route->toSymfonyRoute());
        }

        return $collection;
    }
}
