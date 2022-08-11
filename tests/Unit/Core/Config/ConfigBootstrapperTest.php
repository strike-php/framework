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
    private const TEST_CONFIG_PATH = '/test/config/path';
    private const TEST_CACHED_CONFIG_PATH = '/test/cached/config/path';
    private const TEST_DOTENV_PATH = '/test/.env';
    private const TEST_ADDITIONAL_DOTENV_PATH = '/test/additional/.env';
    private MockObject|ApplicationInterface $application;

    protected function setUp(): void
    {
        $this->application = $this->createMock(ApplicationInterface::class);
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

        $this->configureConfigPath();
        $this->configureCachedConfigPath();
        $this->ensureUsesDotEnvFile();
        $this->ensureBindsConfig($config);
        $configBootstrapper = $this->createPartialMock(ConfigBootstrapper::class, ['createFactory']);
        $configBootstrapper
            ->expects(self::once())
            ->method('createFactory')
            ->willReturn($configFactory);

        $configBootstrapper->bootstrap($this->application);
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

        $this->configureConfigPath();
        $this->configureCachedConfigPath();
        $this->ensureUsesDotEnvFile();
        $this->ensureBindsConfig($config);
        $configBootstrapper = $this->createPartialMock(ConfigBootstrapper::class, ['createFactory']);
        $configBootstrapper
            ->expects(self::once())
            ->method('createFactory')
            ->willReturn($configFactory);

        $configBootstrapper->addDotEnvFile(self::TEST_ADDITIONAL_DOTENV_PATH);
        $configBootstrapper->bootstrap($this->application);
    }

    private function ensureBindsConfig(Config $config): void
    {
        $this->application
            ->expects(self::once())
            ->method('instance')
            ->with(ConfigInterface::class, $config);
    }

    private function ensureUsesDotEnvFile(): void
    {
        $this->application
            ->expects(self::once())
            ->method('getBasePath')
            ->with('.env')
            ->willReturn(self::TEST_DOTENV_PATH);
    }

    private function configureCachedConfigPath(): void
    {
        $this->application
            ->expects(self::once())
            ->method('getCachedConfigPath')
            ->willReturn(self::TEST_CACHED_CONFIG_PATH);
    }

    private function configureConfigPath(): void
    {
        $this->application
            ->expects(self::once())
            ->method('getConfigPath')
            ->willReturn(self::TEST_CONFIG_PATH);
    }
}
