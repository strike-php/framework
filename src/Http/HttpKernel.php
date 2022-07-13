<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http;

use Bambamboole\Framework\Core\Application;
use Bambamboole\Framework\Http\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernel
{
    public function __construct(
        private readonly Application $app,
        private readonly Router $router,
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

        $match = $this->router->match($request);

        $handler = $this->app->get($match->getHandler());

        return \call_user_func($handler, $request, ...$match->getParams());
    }
}
