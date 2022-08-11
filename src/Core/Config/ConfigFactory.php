<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

use Strike\Framework\Core\Environment\EnvironmentLoader;
use Strike\Framework\Core\Filesystem\Filesystem;

class ConfigFactory
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly ConfigLoader $configLoader = new ConfigLoader(),
        private readonly EnvironmentLoader $environmentLoader = new EnvironmentLoader(),
        private readonly bool $enableCache = true,
    ) {
    }

    public function create(string $configFilesPath, string $cachedConfigPath, array $envFiles = []): ConfigInterface
    {
        if (!$this->enableCache) {
            return $this->createFreshConfig($configFilesPath, $envFiles);
        }

        if ($this->filesystem->exists($cachedConfigPath)) {
            return new Config(require $cachedConfigPath);
        }

        $config = $this->createFreshConfig($configFilesPath, $envFiles);
        $this->dumpConfig($cachedConfigPath, $config->all());

        return $config;
    }

    private function createFreshConfig(string $configFilesPath, array $envFiles = []): ConfigInterface
    {
        return $this->configLoader->load(
            $configFilesPath,
            $this->environmentLoader->load($envFiles),
        );
    }

    private function dumpConfig(string $path, array $config): void
    {
        $this->filesystem->put(
            $path,
            '<?php return ' . \var_export($config, true) . ';' . PHP_EOL,
        );
    }
}
