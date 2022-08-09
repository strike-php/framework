<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core\Config;

use Strike\Framework\Core\Config\ConfigFactory;
use Strike\Framework\Core\Config\ConfigLoader;
use Strike\Framework\Core\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Tests\Strike\Framework\Fixtures\HasFixtures;

class ConfigFactoryTest extends TestCase
{
    use HasFixtures;

    public function testItUsesTheCachedFileIfPresent(): void
    {
        $validCacheFileName = $this->getCacheFixturesPath('valid-cache.php');
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
            $this->getConfigFixturePath(),
            $validCacheFileName,
        );
    }

    public function testItWillDumpTheCacheIfItWasInvalid(): void
    {
        $factory = new ConfigFactory();
        $invalidCacheFileName =  $this->getCacheFixturesPath('invalid-config.php');
        self::assertFileDoesNotExist($invalidCacheFileName);

        $factory->create(
            $this->getConfigFixturePath(),
            $this->getCacheFixturesPath('invalid-config.php'),
        );

        self::assertFileExists($invalidCacheFileName);
        \unlink($invalidCacheFileName);
    }
}
