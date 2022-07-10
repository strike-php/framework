<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Config;

use Bambamboole\Framework\Config\ConfigFactory;
use Bambamboole\Framework\Config\ConfigLoader;
use Bambamboole\Framework\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    private string $invalidCacheFileName = __DIR__ . '/../../Fixtures/config/cache/invalid-cache.php';
    private string $validCacheFileName = __DIR__ . '/../../Fixtures/config/cache/valid-cache.php';
    private string $configFilesPath = __DIR__ . '/../../Fixtures/config/config';

    public function testItUsesTheCachedFileIfPresent(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->expects(self::once())
            ->method('exists')
            ->with($this->validCacheFileName)
            ->willReturn(true);
        $configLoader = $this->createMock(ConfigLoader::class);
        $configLoader
            ->expects(self::never())
            ->method('load');

        $factory = new ConfigFactory(
            $filesystem,
            $configLoader,
        );

        $factory->create(
            $this->configFilesPath,
            $this->validCacheFileName,
        );
    }

    public function testItWillDumpTheCacheIfItWasInvalid(): void
    {
        $factory = new ConfigFactory();
        self::assertFileDoesNotExist($this->invalidCacheFileName);

        $factory->create(
            $this->configFilesPath,
            $this->invalidCacheFileName,
        );

        self::assertFileExists($this->invalidCacheFileName);
    }

    protected function tearDown(): void
    {
        @\unlink($this->invalidCacheFileName);
    }
}
