<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Integration\Config;

use Bambamboole\Framework\Config\ConfigLoader;
use Bambamboole\Framework\Environment\Environment;
use PHPUnit\Framework\TestCase;

class ConfigLoaderTest extends TestCase
{
    private string $cacheFileName;

    protected function setUp(): void
    {
        $this->cacheFileName = __DIR__ . '/fixtures/cache/test.php';
        @\unlink($this->cacheFileName);
    }

    protected function tearDown(): void
    {
        @\unlink($this->cacheFileName);
    }

    public function testItCanLoadConfigFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            __DIR__ . '/fixtures/config',
            $this->cacheFileName,
            new Environment(['FOO' => 'bar']),
        );

        self::assertEquals('bar', $config->get('app.test'));
    }

    public function testItCanLoadNestedFiles(): void
    {
        $loader = new ConfigLoader();

        $config = $loader->load(
            __DIR__ . '/fixtures/config',
            $this->cacheFileName,
            new Environment(['AWS_CLIENT_ID' => 'abc123']),
        );

        self::assertEquals('abc123', $config->get('nested.aws.client_id'));
    }

    public function testItLoadsFileFromCacheIfPresent(): void
    {
        $loader = new ConfigLoader();

        self::assertFileDoesNotExist($this->cacheFileName);
        $loader->load(
            __DIR__ . '/fixtures/config',
            $this->cacheFileName,
            new Environment(['AWS_CLIENT_ID' => 'abc123']),
        );

        self::assertFileExists($this->cacheFileName);
        $loader->load(
            __DIR__ . '/fixtures/config',
            $this->cacheFileName,
            new Environment(['AWS_CLIENT_ID' => 'abc123']),
        );
    }
}
