<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core\Config;

use Monolog\Test\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\Config;
use Strike\Framework\Core\Config\ConfigBootstrapper;
use Strike\Framework\Core\Config\ConfigFactory;
use Strike\Framework\Core\Config\ConfigInterface;

class ConfigBootstrapperTest extends TestCase
{
    private ConfigBootstrapper $configBoostrapper;
    private const TEST_CONFIG_PATH = '/test/config/path';
    private const TEST_CACHED_CONFIG_PATH = '/test/cached/config/path';
    private const TEST_DOTENV_PATH = '/test/.env';
    private const TEST_ADDITIONAL_DOTENV_PATH = '/test/additional/.env';

    protected function setUp(): void
    {
        $this->configBoostrapper = new ConfigBootstrapper();
    }

    public function testItUsesDotEnvFileToLoadConfig(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::TEST_CONFIG_PATH,
                self::TEST_CACHED_CONFIG_PATH,
                self::callback(fn ($files) => \count($files) === 1 && $files[0]->getPathname() === self::TEST_DOTENV_PATH),
            )->willReturn($config);

        $application = $this->createMock(ApplicationInterface::class);
        $this->configureConfigPath($application);
        $this->configureCachedConfigPath($application);
        $this->ensureUsesDotEnvFile($application);
        $this->ensureBindsConfig($application, $config);
        $application
            ->expects(self::once())
            ->method('get')
            ->with(ConfigFactory::class)
            ->willReturn($configFactory);

        $this->configBoostrapper->bootstrap($application);
    }

    public function testItUsesDotEnvFileToLoadConfigIncludingExtraDotEnvFiles(): void
    {
        $config = new Config();
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::TEST_CONFIG_PATH,
                self::TEST_CACHED_CONFIG_PATH,
                self::callback(fn ($files) => \count($files) === 2 && $files[1]->getPathname() === self::TEST_ADDITIONAL_DOTENV_PATH),
            )->willReturn($config);

        $application = $this->createMock(ApplicationInterface::class);

        $this->configureConfigPath($application);
        $this->configureCachedConfigPath($application);
        $this->ensureUsesDotEnvFile($application);
        $this->ensureBindsConfig($application, $config);
        $application
            ->expects(self::once())
            ->method('get')
            ->with(ConfigFactory::class)
            ->willReturn($configFactory);

        $this->configBoostrapper->addDotEnvFile(self::TEST_ADDITIONAL_DOTENV_PATH);
        $this->configBoostrapper->bootstrap($application);
    }

    private function ensureBindsConfig(ApplicationInterface|MockObject $application, Config $config): void
    {
        $application
            ->expects(self::once())
            ->method('instance')
            ->with(ConfigInterface::class, $config);
    }

    private function ensureUsesDotEnvFile(ApplicationInterface|MockObject $application): void
    {
        $application
            ->expects(self::once())
            ->method('getBasePath')
            ->with('.env')
            ->willReturn(self::TEST_DOTENV_PATH);
    }

    private function configureCachedConfigPath(ApplicationInterface|MockObject $application): void
    {
        $application
            ->expects(self::once())
            ->method('getCachedConfigPath')
            ->willReturn(self::TEST_CACHED_CONFIG_PATH);
    }

    private function configureConfigPath(ApplicationInterface|MockObject $application): void
    {
        $application
            ->expects(self::once())
            ->method('getConfigPath')
            ->willReturn(self::TEST_CONFIG_PATH);
    }
}
