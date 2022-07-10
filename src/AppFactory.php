<?php

declare(strict_types=1);

namespace Bambamboole\Framework;

use Bambamboole\Framework\Config\ConfigFactory;
use Bambamboole\Framework\Filesystem\Filesystem;

class AppFactory
{
    private string $configFilesPath = 'config';
    private string $cachedConfigPath = 'bootstrap/cache/config.php';
    private array $envFiles = [];

    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly ConfigFactory $configFactory = new ConfigFactory(),
    ) {
    }

    public function withConfigFilesPath(string $configFilesPath): self
    {
        $this->configFilesPath = $configFilesPath;

        return $this;
    }

    public function withCachedConfigPath(string $cachedConfigPath): self
    {
        $this->cachedConfigPath = $cachedConfigPath;

        return $this;
    }

    public function withEnvFile(string $envFile): self
    {
        if (!$this->filesystem->exists($envFile)) {
            return $this;
        }

        $this->envFiles[] = new \SplFileInfo($envFile);

        return $this;
    }

    public function create(string $basePath): Application
    {
        return new Application(
            $basePath,
            $this->configFactory->create(
                $basePath . DIRECTORY_SEPARATOR . $this->configFilesPath,
                $basePath . DIRECTORY_SEPARATOR . $this->cachedConfigPath,
                $this->envFiles,
            ),
        );
    }
}
