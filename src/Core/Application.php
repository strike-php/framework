<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Strike\Framework\Core\Config\Config;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Container\Container;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Exception\ExceptionHandler;
use Strike\Framework\Core\Exception\ExceptionHandlerInterface;
use Strike\Framework\Core\Exception\IncompatibleModuleException;

class Application implements ContainerInterface
{
    /** @var ModuleInterface[] */
    private array $modules = [];
    private bool $booted = false;

    public function __construct(
        private readonly string $basePath,
        private readonly ContainerInterface $container = new Container(),
        private readonly ConfigInterface $config = new Config(),
    ) {
        \date_default_timezone_set($this->config->get('app.timezone', 'UTC'));
        $this->config->set('app.base_path', $this->basePath);
        $this->registerBaseBindings();
    }

    public function getBasePath(?string $path = null): string
    {
        return empty($path)
            ? $this->basePath
            : $this->basePath . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }

    public function bind(string $key, string|\Closure $implementation, bool $isShared = false): void
    {
        $this->container->bind($key, $implementation, $isShared);
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

    public function register(string $moduleClass): void
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

    public function boot(): void
    {
        if (!$this->booted) {
            foreach ($this->modules as $module) {
                $module->load();
            }
            $this->booted = true;
        }
    }

    private function registerBaseBindings(): void
    {
        $this->container->instance(Application::class, $this);
        $this->container->instance(ConfigInterface::class, $this->config);
        $this->container->bind(
            ExceptionHandlerInterface::class,
            fn (ContainerInterface $container) => new ExceptionHandler(
                $container->get(ConfigInterface::class)->get('app.debug', false),
            ),
        );
    }
}
