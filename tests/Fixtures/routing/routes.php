<?php

declare(strict_types=1);

/** @var $routes \Strike\Framework\Http\Routing\RouteRegistrar */

$routes
    ->get('/', \Tests\Strike\Framework\Fixtures\Classes\TestController::class)
    ->setName('test.routes');

$routes
    ->post('/{id}', \Tests\Strike\Framework\Fixtures\Classes\TestController::class)
    ->setName('test.routes.post');
