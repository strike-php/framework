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
    ) {
    }

    public function create(string $configFilesPath, string $cachedConfigPath, array $envFiles = []): ConfigInterface
    {
        if ($this->filesystem->exists($cachedConfigPath)) {
            return new Config(require $cachedConfigPath);
        }

        $config = $this->configLoader->load(
            $configFilesPath,
            $this->environmentLoader->load($envFiles),
        );

        $this->dumpConfig($cachedConfigPath, $config->all());

        return $config;
    }

    private function dumpConfig(string $path, array $config): void
    {
        $this->filesystem->put(
            $path,
            '<?php return ' . \var_export($config, true) . ';' . PHP_EOL,
        );
    }
}
