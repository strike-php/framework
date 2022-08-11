<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\BootstrapperInterface;
use Strike\Framework\Core\Environment\EnvironmentLoader;
use Strike\Framework\Core\Filesystem\Filesystem;

class ConfigBootstrapper implements BootstrapperInterface
{
    private array $additionalDotEnvFiles = [];

    private bool $enableConfigCache = true;
    private ?\Closure $configCallback = null;

    public function addDotEnvFile(string $filePath): self
    {
        $this->additionalDotEnvFiles[] = new \SplFileInfo($filePath);

        return $this;
    }

    public function disableConfigCache(): self
    {
        $this->enableConfigCache = false;

        return $this;
    }

    public function configure(\Closure $configCallback): self
    {
        $this->configCallback = $configCallback;

        return $this;
    }

    public function bootstrap(ApplicationInterface $application): void
    {
        $config = $this->createConfig($application);
        if ($this->configCallback) {
            \call_user_func($this->configCallback, $config);
        }
        \date_default_timezone_set($config->get('app.timezone', 'UTC'));
        $application->instance(ConfigInterface::class, $config);
    }

    protected function createFactory(): ConfigFactory
    {
        return new ConfigFactory(
            new Filesystem(),
            new ConfigLoader(),
            new EnvironmentLoader(),
            $this->enableConfigCache,
        );
    }

    private function createConfig(ApplicationInterface $app): ConfigInterface
    {
        return $this->createFactory()->create(
            $app->getConfigPath(),
            $app->getCachedConfigPath(),
            \array_merge([new \SplFileInfo($app->getBasePath('.env'))], $this->additionalDotEnvFiles),
        );
    }
}
