<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Http\Middleware;

use Bambamboole\Framework\Core\Container\Container;
use Bambamboole\Framework\Http\Middleware\MiddlewareInterface;
use Bambamboole\Framework\Http\Middleware\MiddlewareStack;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareStackTest extends TestCase
{
    public function testItExecutesTheStackCorrectly(): void
    {
        $request = new Request();
        $response = new Response();
        $container = new Container();
        $middleware = $this->createMock(MiddlewareInterface::class);
        $container->instance(MiddlewareInterface::class, $middleware);
        $middleware
            ->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);
        $stack = new MiddlewareStack($container);

        $result = $stack
            ->sendRequest($request)
            ->through(MiddlewareInterface::class)
            ->then(fn () => $response);

        self::assertSame($response, $result);
    }
}

class TestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): Response
    {
        return new Response();
    }
}
