<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Strike\Framework\Core\Config\ConfigFactory;
use Strike\Framework\Core\Container\Container;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Http\Routing\RoutingModule;
use Strike\Framework\Log\LoggingModule;

class AppFactory
{
    private string $configFilesPath = 'config';
    private string $cachedConfigPath = 'bootstrap/cache/config.php';
    private array $envFiles = [];

    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
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

    public function create(string $basePath, ContainerInterface $container = new Container()): Application
    {
        // we pre-bind the Filesystem on the container
        // since it will be required many times
        $container->instance(Filesystem::class, new Filesystem());

        $app =  new Application(
            $basePath,
            $container,
            $container->get(ConfigFactory::class)->create(
                $this->getPath($basePath, $this->configFilesPath),
                $this->getPath($basePath, $this->cachedConfigPath),
                $this->envFiles,
            ),
        );

        $app->registerModule(RoutingModule::class);
        $app->registerModule(LoggingModule::class);

        return $app;
    }

    private function getPath(string $basePath, string $path): string
    {
        if (\str_starts_with($path, '/') || \str_starts_with($path, '\\')) {
            return $path;
        }

        return empty($path)
            ? $basePath
            : $basePath . DIRECTORY_SEPARATOR . $path;
    }
}
