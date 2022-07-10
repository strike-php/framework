<?php

declare(strict_types=1);

namespace Bambamboole\Framework;

use Bambamboole\Framework\Config\Config;
use Bambamboole\Framework\Config\ConfigLoader;
use Bambamboole\Framework\Environment\Environment;

class Application
{
    private ?Config $config = null;

    public function __construct(
        private readonly string $basePath,
        private readonly Environment $environment = new Environment(),
        private readonly ConfigLoader $configLoader = new ConfigLoader(),
    ) {
    }

    public function configure(?Config $config = null): void
    {
        if ($config) {
            $this->config = $config;

            return;
        }
        $this->config = $this->configLoader
            ->load(
                $this->getConfigPath(),
                $this->getCachedConfigPath(),
                $this->environment,
            );
    }

    public function getConfigPath(): string
    {
        return $this->getBasePath($this->environment->get('APP_CONFIG_PATH', 'config'));
    }

    public function getCachedConfigPath(): string
    {
        $pathFromEnv = $this->environment->get('APP_CACHED_CONFIG', 'bootstrap/cache/config.php');

        return \str_starts_with($pathFromEnv, '/')
            ? $pathFromEnv
            : $this->getBasePath($pathFromEnv);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getBasePath(?string $path = null): string
    {
        return empty($path)
            ? $this->basePath
            : $this->basePath . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }
}
