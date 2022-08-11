<?php

declare(strict_types=1);

use Strike\Framework\Http\Routing\RouteRegistrar;
use Tests\Strike\Framework\Fixtures\Application\App\Http\Middleware\TestMiddleware;
use Tests\Strike\Framework\Fixtures\Application\App\Http\TestController;

/** @var $routes RouteRegistrar */

$routes
    ->get('/', TestController::class)
    ->setName('test.routes');

$routes
    ->post('/{id}', TestController::class)
    ->middleware(TestMiddleware::class)
    ->setName('test.routes.post');
