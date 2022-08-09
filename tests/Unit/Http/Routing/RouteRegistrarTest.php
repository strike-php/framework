<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http\Routing;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Http\Routing\HttpMethod;
use Strike\Framework\Http\Routing\RouteRegistrar;

class RouteRegistrarTest extends TestCase
{
    public function testItCanRegisterGETRoutes(): void
    {
        $registrar = new RouteRegistrar();

        $route = $registrar->get('/', '');

        self::assertEquals(HttpMethod::GET, $route->getHttpMethod());
        self::assertSame($registrar->getRoutes()[0], $route);
    }

    public function testItCanRegisterHEADRoutes(): void
    {
        $registrar = new RouteRegistrar();

        $route = $registrar->head('/', '');

        self::assertEquals(HttpMethod::HEAD, $route->getHttpMethod());
        self::assertSame($registrar->getRoutes()[0], $route);
    }

    public function testItCanRegisterPATCHRoutes(): void
    {
        $registrar = new RouteRegistrar();

        $route = $registrar->patch('/', '');

        self::assertEquals(HttpMethod::PATCH, $route->getHttpMethod());
        self::assertSame($registrar->getRoutes()[0], $route);
    }

    public function testItCanRegisterPUTRoutes(): void
    {
        $registrar = new RouteRegistrar();

        $route = $registrar->put('/', '');

        self::assertEquals(HttpMethod::PUT, $route->getHttpMethod());
        self::assertSame($registrar->getRoutes()[0], $route);
    }

    public function testItCanRegisterPOSTRoutes(): void
    {
        $registrar = new RouteRegistrar();

        $route = $registrar->post('/', '');

        self::assertEquals(HttpMethod::POST, $route->getHttpMethod());
        self::assertSame($registrar->getRoutes()[0], $route);
    }
}
