<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Integration;

use Strike\Framework\Testing\IntegrationTestCase as StrikeTestCase;

class IntegrationTestCase extends StrikeTestCase
{
    protected function setUp(): void
    {
        $this->app = require \dirname(__DIR__) . '/Fixtures/Application/etc/bootstrap.php';
    }
}
