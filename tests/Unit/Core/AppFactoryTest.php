<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Core;

use Bambamboole\Framework\Core\AppFactory;
use Bambamboole\Framework\Core\Config\Config;
use Bambamboole\Framework\Core\Config\ConfigFactory;
use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Core\Container\Container;
use PHPUnit\Framework\TestCase;
use Tests\Bambamboole\Framework\Fixtures\HasFixtures;

class AppFactoryTest extends TestCase
{
    use HasFixtures;

    public function testItCanAddMoreDotEnvFiles(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::anything(),
                [new \SplFileInfo($this->getEnvironmentFixturePath('.env'))],
            )
            ->willReturn($config);
        $container = new Container();
        $container->instance(ConfigFactory::class, $configFactory);
        $factory = new AppFactory();

        $app = $factory
            ->withEnvFile($this->getEnvironmentFixturePath('.env'))
            ->create($this->getFixturePath(), $container);

        self::assertSame($config, $app->get(ConfigInterface::class));
    }

    public function testItDoesNotExistNonExistingDotEnvFiles(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::anything(),
                [],
            )
            ->willReturn($config);
        $container = new Container();
        $container->instance(ConfigFactory::class, $configFactory);

        $app = (new AppFactory())
            ->withEnvFile(__DIR__ . '/does-not-exist/.env')
            ->create($this->getFixturePath(), $container);

        self::assertSame($config, $app->get(ConfigInterface::class));
    }

    public function testItCanChangeTHePathToConfigFiles(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                $this->getFixturePath('changed-path'),
                self::anything(),
                [],
            )
            ->willReturn($config);
        $container = new Container();
        $container->instance(ConfigFactory::class, $configFactory);

        $app = (new AppFactory())
            ->withConfigFilesPath('changed-path')
            ->create($this->getFixturePath(), $container);

        self::assertSame($config, $app->get(ConfigInterface::class));
    }

    public function testItCanChangeThePathToTheCachedConfigFile(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                $this->getFixturePath('cache/config.php'),
                [],
            )
            ->willReturn($config);
        $container = new Container();
        $container->instance(ConfigFactory::class, $configFactory);

        $app = (new AppFactory())
            ->withCachedConfigPath('cache/config.php')
            ->create($this->getFixturePath(), $container);

        self::assertSame($config, $app->get(ConfigInterface::class));
    }
}
