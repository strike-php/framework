<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Core\Config;

use Bambamboole\Framework\Core\Config\ConfigFactory;
use Bambamboole\Framework\Core\Config\ConfigLoader;
use Bambamboole\Framework\Core\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Tests\Bambamboole\Framework\Fixtures\HasFixtures;

class ConfigFactoryTest extends TestCase
{
    use HasFixtures;

    public function testItUsesTheCachedFileIfPresent(): void
    {
        $validCacheFileName = $this->getConfigFixturePath('cache/valid-cache.php');
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->expects(self::once())
            ->method('exists')
            ->with($validCacheFileName)
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
            $this->getConfigFixturePath('config'),
            $validCacheFileName,
        );
    }

    public function testItWillDumpTheCacheIfItWasInvalid(): void
    {
        $factory = new ConfigFactory();
        $invalidCacheFileName = $this->getConfigFixturePath('cache/invalid-config.php');
        self::assertFileDoesNotExist($invalidCacheFileName);

        $factory->create(
            $this->getConfigFixturePath('config'),
            $this->getConfigFixturePath('cache/invalid-config.php'),
        );

        self::assertFileExists($invalidCacheFileName);
        \unlink($invalidCacheFileName);
    }
}
