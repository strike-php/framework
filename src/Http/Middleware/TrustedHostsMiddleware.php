<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http\Middleware;

use Bambamboole\Framework\Core\Config\ConfigInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustedHostsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ConfigInterface $config)
    {
    }

    public function handle(Request $request, \Closure $next): Response
    {
        if ($this->shouldSpecifyTrustedHosts()) {
            $request::setTrustedHosts($this->getHosts());
        }

        return $next($request);
    }

    private function getHosts(): array
    {
        if ($host = \parse_url($this->config->get('app.url'), PHP_URL_HOST)) {
            return ['^(.+\.)?' . \preg_quote($host) . '$'];
        }

        return [];
    }

    private function shouldSpecifyTrustedHosts(): bool
    {
        return false;
    }
}
