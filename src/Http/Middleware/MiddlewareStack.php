<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http\Middleware;

use Bambamboole\Framework\Core\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareStack
{
    private Request $request;
    private array $middleware = [];

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function sendRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function through(array|string $middleware): self
    {
        $this->middleware = (array)$middleware;

        return $this;
    }

    public function then(\Closure $destination): Response
    {
        $pipeline = \array_reduce(
            \array_reverse($this->middleware),
            fn (\Closure $nextClosure, string $middlewareClass) => $this->createHandler($nextClosure, $middlewareClass)->handle(...),
            fn (Request $request) => $destination($request),
        );

        return $pipeline($this->request);
    }

    private function createHandler(\Closure $nextClosure, string $middlewareClass): MiddlewareHandler
    {
        return new MiddlewareHandler(fn () => $this->container->get($middlewareClass), $nextClosure);
    }
}
