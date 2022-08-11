<?php

declare(strict_types=1);

namespace Strike\Framework\Http;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Exception\ExceptionHandlerInterface;
use Strike\Framework\Http\Middleware\MiddlewareStack;
use Strike\Framework\Http\Middleware\TrustedHostsMiddleware;
use Strike\Framework\Http\Middleware\TrustedProxiesMiddleware;
use Strike\Framework\Http\Routing\HttpMatch;
use Strike\Framework\Http\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernel
{
    public function __construct(
        private readonly ApplicationInterface $app,
    ) {
    }

    public function boot(): void
    {
        $this->app->boot();
    }

    public function handle(Request $request): Response
    {
        try {
            $this->boot();
            $this->app->instance(Request::class, $request);

            return (new MiddlewareStack($this->app))
                ->sendRequest($request)
                ->through($this->getDefaultMiddlewares())
                ->then($this->executeDestination(...));
        } catch (\Throwable $e) {
            return $this->app->get(ExceptionHandlerInterface::class)->handleException($e);
        }
    }

    private function getDefaultMiddlewares(): array
    {
        return $this->app->get(ConfigInterface::class)->get(
            'http.middlewares',
            [TrustedHostsMiddleware::class, TrustedProxiesMiddleware::class],
        );
    }

    private function executeDestination(Request $request): Response
    {
        /** @var HttpMatch $match */
        $match = $this->app->get(Router::class)->match($request);

        return (new MiddlewareStack($this->app))
            ->sendRequest($request)
            ->through($match->getMiddleware())
            ->then(
                function (Request $request) use ($match) {
                    return \call_user_func(
                        $this->app->get($match->getHandler()),
                        $request,
                        ...$match->getParams(),
                    );
                },
            );
    }
}
