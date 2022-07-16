<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http\Middleware;

use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Http\Middleware\TrustedProxiesMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustedProxiesMiddlewareTest extends TestCase
{
    public function testItSetsCurrentRemoteAddressAsTrustedProxiesIfConfiguredWithAsterisk(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects(self::once())
            ->method('get')
            ->with('http.trusted_proxies')
            ->willReturn('*');
        $middleware = new TrustedProxiesMiddleware($config);
        $request = new Request(server: ['REMOTE_ADDR' => '1.1.1.1']);

        $middleware->handle($request, fn () => new Response());

        self::assertCount(1, $request->getTrustedProxies());
        self::assertEquals('1.1.1.1', $request->getTrustedProxies()[0]);
    }

    public function testItSetsTrustedProxiesAsConfigured(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects(self::once())
            ->method('get')
            ->with('http.trusted_proxies')
            ->willReturn(['1.1.1.1', '1.2.3.4']);
        $middleware = new TrustedProxiesMiddleware($config);
        $request = new Request();

        $middleware->handle($request, fn () => new Response());

        self::assertCount(2, $request->getTrustedProxies());
        self::assertEquals('1.1.1.1', $request->getTrustedProxies()[0]);
        self::assertEquals('1.2.3.4', $request->getTrustedProxies()[1]);
    }
}
