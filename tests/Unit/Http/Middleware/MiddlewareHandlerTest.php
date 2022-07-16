<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http\Middleware;

use Strike\Framework\Http\Middleware\MiddlewareException;
use Strike\Framework\Http\Middleware\MiddlewareHandler;
use Strike\Framework\Http\Middleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareHandlerTest extends TestCase
{
    public function testItExecutesTheResolvedMiddleware(): void
    {
        $next = fn () => new Response();
        $request = $this->createMock(Request::class);
        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware
            ->expects(self::once())
            ->method('handle')
            ->with($request, $next)
            ->willReturn(new Response());

        $handler = new MiddlewareHandler(fn () => $middleware, $next);

        $handler->handle($request);
    }

    public function testItThrowsAnExceptionIfItDoesNotImplementTheRightInterface(): void
    {
        $request = $this->createMock(Request::class);
        $middleware = $this->createMock(\stdClass::class);
        self::expectException(MiddlewareException::class);

        $handler = new MiddlewareHandler(fn () => $middleware, fn () => new Response());

        $handler->handle($request);
    }
}
