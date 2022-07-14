<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http;

use Bambamboole\Framework\Core\Application;
use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Http\Middleware\MiddlewareStack;
use Bambamboole\Framework\Http\Middleware\TrustedHostsMiddleware;
use Bambamboole\Framework\Http\Middleware\TrustedProxiesMiddleware;
use Bambamboole\Framework\Http\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernel
{
    public function __construct(
        private readonly Application $app,
        private readonly Router $router,
        private readonly ConfigInterface $config,
    )
    {
    }

    public function boot(): void
    {
        $this->app->boot();
    }

    public function handle(Request $request): Response
    {
        $this->boot();
        $this->app->instance(Request::class, $request);

        return (new MiddlewareStack($this->app))
            ->sendRequest($request)
            ->through($this->getDefaultMiddlewares())
            ->then(
                function (Request $request) {
                    $match = $this->router->match($request);

                    return \call_user_func(
                        $this->app->get($match->getHandler()),
                        $request,
                        ...$match->getParams(),
                    );
                },
            );
    }

    private function getDefaultMiddlewares(): array
    {
        return $this->config->get(
            'http.middlewares',
            [TrustedHostsMiddleware::class, TrustedProxiesMiddleware::class]
        );
    }
}
