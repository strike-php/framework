<?php

declare(strict_types=1);

/** @var $routes \Bambamboole\Framework\Http\Routing\RouteRegistrar */

$routes
    ->get('/', \Tests\Bambamboole\Framework\Fixtures\Classes\TestController::class)
    ->setName('test.routes');

$routes
    ->post('/{id}', \Tests\Bambamboole\Framework\Fixtures\Classes\TestController::class)
    ->setName('test.routes.post');
