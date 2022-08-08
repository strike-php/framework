<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Psr\Container\ContainerInterface as PsrContainerInterface;
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
    private ApplicationPathsInterface $path;

    public function __construct(
        string|ApplicationPathsInterface $path,
        private readonly ContainerInterface $container = new Container(),
        private readonly array $bootstrappers = [
            ConfigBootstrapper::class,
            ExceptionBootstrapper::class,
            ModuleBootstrapper::class,
        ],
    ) {
        $this->path = \is_string($path) ? new ApplicationPaths($path) : $path;
        $this->registerBaseBindings();
    }

    public function getBasePath(?string $path = null): string
    {
        return $this->path->getBasePath($path);
    }

    public function getConfigPath(): string
    {
        return $this->path->getConfigPath();
    }

    public function getRoutesPath(): string
    {
        return $this->path->getRoutesPath();
    }

    public function getCachedConfigPath(): string
    {
        return $this->path->getCachedConfigPath();
    }

    public function getCachedRoutesPath(): string
    {
        return $this->path->getCachedRoutesPath();
    }

    public function getDocumentRoot(): string
    {
        return $this->path->getDocumentRoot();
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
        $this->container->instance(ApplicationPathsInterface::class, $this->path);
        $this->container->instance(ApplicationInterface::class, $this);
        $this->container->instance(ContainerInterface::class, $this);
        $this->container->instance(PsrContainerInterface::class, $this);
    }
}
