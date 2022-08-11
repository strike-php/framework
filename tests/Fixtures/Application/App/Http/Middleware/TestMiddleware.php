<?php

namespace Tests\Strike\Framework\Fixtures\Application\App\Http\Middleware;

use Strike\Framework\Http\Middleware\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, \Closure $next): Response
    {
        return $next($request);
    }
}
