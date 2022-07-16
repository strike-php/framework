<?php

declare(strict_types=1);

namespace Strike\Framework\Http;

use Strike\Framework\Core\Application;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Http\Middleware\MiddlewareStack;
use Strike\Framework\Http\Middleware\TrustedHostsMiddleware;
use Strike\Framework\Http\Middleware\TrustedProxiesMiddleware;
use Strike\Framework\Http\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernel
{
    public function __construct(
        private readonly Application $app,
        private readonly Router $router,
        private readonly ConfigInterface $config,
    ) {
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
            [TrustedHostsMiddleware::class, TrustedProxiesMiddleware::class],
        );
    }
}
