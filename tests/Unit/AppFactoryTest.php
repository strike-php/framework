<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit;

use Bambamboole\Framework\AppFactory;
use Bambamboole\Framework\Config\Config;
use Bambamboole\Framework\Config\ConfigFactory;
use PHPUnit\Framework\TestCase;

class AppFactoryTest extends TestCase
{
    private const BASE_PATH = __DIR__ . '/../Fixtures';

    public function testItCanAddMoreDotEnvFiles(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::anything(),
                [new \SplFileInfo(__DIR__ . '/../Fixtures/environment/.env')],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withEnvFile(__DIR__ . '/../Fixtures/config/config')
            ->create(self::BASE_PATH);
    }

    public function testItDoesNotExistNonExistingDotEnvFiles(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::anything(),
                [],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withEnvFile(__DIR__ . '/does-not-exist/.env')
            ->create(self::BASE_PATH);
    }

    public function testItCanChangeTHePathToConfigFiles(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::BASE_PATH . DIRECTORY_SEPARATOR . 'changed-path',
                self::anything(),
                [],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withConfigFilesPath('changed-path')
            ->create(self::BASE_PATH);
    }

    public function testItCanChangeThePathToTheCachedConfigFile(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::BASE_PATH . DIRECTORY_SEPARATOR . 'cache/config.php',
                [],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withCachedConfigPath('cache/config.php')
            ->create(self::BASE_PATH);
    }
}
