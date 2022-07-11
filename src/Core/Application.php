<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Core;

use Bambamboole\Framework\Core\Config\Config;
use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Core\Container\Container;
use Bambamboole\Framework\Core\Container\ContainerInterface;

class Application implements ContainerInterface
{
    public function __construct(
        private readonly string $basePath,
        private ConfigInterface $config = new Config(),
        private ContainerInterface $container = new Container(),
    ) {
        $this->container->instance(ConfigInterface::class, $this->config);
        $this->configure();
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

    protected function configure(): void
    {
        \date_default_timezone_set($this->config->get('app.timezone', 'UTC'));

        foreach ($this->config->get('app.modules') ?? [] as $moduleClass) {
            /** @var ModuleInterface $module */
            $module = $this->get($moduleClass);
            $module->load($this);
        }
    }
}
