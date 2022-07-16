<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Middleware;

use Strike\Framework\Core\Config\ConfigInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustedProxiesMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ConfigInterface $config)
    {
    }

    public function handle(Request $request, \Closure $next): Response
    {
        $request::setTrustedProxies(
            $this->getTrustedIps($request),
            $this->getTrustedHeaderNames(),
        );

        return $next($request);
    }

    private function getTrustedIps(Request $request): array
    {
        $trustedIps = $this->config->get('http.trusted_proxies', []);
        if ($trustedIps === '*') {
            $trustedIps = $request->server->get('REMOTE_ADDR');
        }

        return (array)$trustedIps;
    }

    private function getTrustedHeaderNames(): int
    {
        return Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_PREFIX
            | Request::HEADER_X_FORWARDED_AWS_ELB;
    }
}
