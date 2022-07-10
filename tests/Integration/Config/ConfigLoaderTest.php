<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Integration\Config;

use Bambamboole\Framework\Config\ConfigLoader;
use Bambamboole\Framework\Environment\Environment;
use PHPUnit\Framework\TestCase;

class ConfigLoaderTest extends TestCase
{
    private string $cacheFileName = __DIR__ . '/../../Fixtures/cache/test.php';
    private string $configFilesPath = __DIR__ . '/../../Fixtures/config/config';

    protected function tearDown(): void
    {
        @\unlink($this->cacheFileName);
    }

    public function testItCanLoadConfigFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            $this->configFilesPath,
            new Environment(['FOO' => 'bar']),
        );

        self::assertEquals('bar', $config->get('app.test'));
    }

    public function testItCanLoadNestedFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            $this->configFilesPath,
            new Environment(['AWS_CLIENT_ID' => 'abc123']),
        );

        self::assertEquals('abc123', $config->get('nested.aws.client_id'));
    }
}
