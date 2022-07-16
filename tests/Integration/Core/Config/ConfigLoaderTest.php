<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Integration\Core\Config;

use Strike\Framework\Core\Config\ConfigLoader;
use Strike\Framework\Core\Environment\Environment;
use PHPUnit\Framework\TestCase;
use Tests\Strike\Framework\Fixtures\HasFixtures;

class ConfigLoaderTest extends TestCase
{
    use HasFixtures;

    public function testItCanLoadConfigFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            $this->getConfigFixturePath(),
            new Environment(['FOO' => 'bar']),
        );

        self::assertEquals('bar', $config->get('app.test'));
    }

    public function testItCanLoadNestedFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            $this->getConfigFixturePath(),
            new Environment(['AWS_CLIENT_ID' => 'abc123']),
        );

        self::assertEquals('abc123', $config->get('nested.aws.client_id'));
    }
}
