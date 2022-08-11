<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Integration\Http\Routing;

use Strike\Framework\Http\Routing\HttpMethod;
use Tests\Strike\Framework\Integration\IntegrationTestCase;

class RouteTest extends IntegrationTestCase
{
    public function test(): void
    {
        $response = $this->call(HttpMethod::GET, '/');

        $response->assertBodyContains('works');
    }
}
