<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Strike\Framework\Core\Config\ConfigBootstrapper;
use Strike\Framework\Core\Container\Container;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Exception\ExceptionBootstrapper;
use Strike\Framework\Core\Exception\IncompatibleBootstrapperException;
use Strike\Framework\Core\Exception\IncompatibleModuleException;

class Application implements ApplicationInterface
{
    /** @var ModuleInterface[] */
    private array $modules = [];
    private bool $booted = false;
    private array $commands = [];

    public function __construct(
        private readonly string $basePath,
        private readonly ContainerInterface $container = new Container(),
        private readonly array $bootstrappers = [
            ConfigBootstrapper::class,
            ExceptionBootstrapper::class,
            ModuleBootstrapper::class,
        ],
    ) {
        $this->registerBaseBindings();
    }

    public function getBasePath(?string $path = null): string
    {
        return empty($path)
            ? $this->basePath
            : $this->basePath . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }

    public function getConfigPath(): string
    {
        return $this->getBasePath('config');
    }

    public function getCachedConfigPath(): string
    {
        return $this->getBasePath('bootstrap/cache/config.php');
    }

    public function getRoutesPath(): string
    {
        return $this->getBasePath('routes.php');
    }

    public function getCachedRoutesPath(): string
    {
        return $this->getBasePath('bootstrap/cache/routes.php');
    }

    public function bind(string $key, string|\Closure $implementation, bool $isShared = false): void
    {
        $this->container->bind($key, $implementation, $isShared);
    }

    public function singleton(string $key, string|\Closure $implementation): void
    {
        $this->container->singleton($key, $implementation);
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function instance(string $key, mixed $instance): void
    {
        $this->container->instance($key, $instance);
    }

    public function registerModule(string $moduleClass): void
    {
        if (\array_key_exists($moduleClass, $this->modules)) {
            return;
        }

        $module = $this->get($moduleClass);

        if (!$module instanceof ModuleInterface) {
            throw new IncompatibleModuleException(
                \sprintf('The class "%s" does not implement "%s"', $moduleClass, ModuleInterface::class),
            );
        }

        $module->register();

        $this->modules[$moduleClass] = $module;

        if ($this->booted) {
            $module->load();
        }
    }

    public function runningInConsole(): bool
    {
        return \PHP_SAPI === 'cli';
    }

    public function registerCommand(string $command, ?\Closure $factory = null): void
    {
        if (!$this->runningInConsole()) {
            return;
        }

        $this->commands[$command] = $factory;
    }

    public function getRegisteredCommands(): array
    {
        return $this->commands;
    }

    public function boot(array $additionalBootstrappers = []): void
    {
        if ($this->booted) {
            return;
        }

        foreach (\array_merge($this->bootstrappers, $additionalBootstrappers) as $bootstrapperClass) {
            if ($bootstrapperClass instanceof BootstrapperInterface) {
                $bootstrapperClass->bootstrap($this);
                continue;
            }
            $bootstrapper = $this->get($bootstrapperClass);
            if (!$bootstrapper instanceof BootstrapperInterface) {
                throw new IncompatibleBootstrapperException(
                    $bootstrapperClass . ' does not implement ' . BootstrapperInterface::class,
                );
            }
            $bootstrapper->bootstrap($this);
        }

        foreach ($this->modules as $module) {
            $module->load();
        }

        $this->booted = true;
    }

    protected function registerBaseBindings(): void
    {
        $this->container->instance(Application::class, $this);
        $this->container->instance(ApplicationInterface::class, $this);
    }
}
