<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Core\Unit;

use Bambamboole\Framework\Core\AppFactory;
use Bambamboole\Framework\Core\Config\Config;
use Bambamboole\Framework\Core\Config\ConfigFactory;
use PHPUnit\Framework\TestCase;
use Tests\Bambamboole\Framework\Fixtures\HasFixtures;

class AppFactoryTest extends TestCase
{
    use HasFixtures;
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
                [new \SplFileInfo($this->getEnvironmentFixturePath('.env'))],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withEnvFile($this->getEnvironmentFixturePath('.env'))
            ->create($this->getFixturePath());
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
            ->create($this->getFixturePath());
    }

    public function testItCanChangeTHePathToConfigFiles(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                $this->getConfigFixturePath('changed-path'),
                self::anything(),
                [],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withConfigFilesPath('changed-path')
            ->create($this->getFixturePath());
    }

    public function testItCanChangeThePathToTheCachedConfigFile(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                $this->getFixturePath('cache/config.php'),
                [],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withCachedConfigPath('cache/config.php')
            ->create($this->getFixturePath());
    }
}
