<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareHandler
{
    public function __construct(
        private readonly \Closure $middlewareResolver,
        private readonly \Closure $nextHandler,
    ) {
    }

    public function handle(Request $request): Response
    {
        $middleware = \call_user_func($this->middlewareResolver);

        if (!$middleware instanceof MiddlewareInterface) {
            throw MiddlewareException::notImplemented($middleware);
        }

        return $middleware->handle($request, $this->nextHandler);
    }
}
