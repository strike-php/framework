<?php

declare(strict_types=1);

namespace Bambamboole\Framework;

use Bambamboole\Framework\Config\Config;
use Bambamboole\Framework\Config\ConfigInterface;
use Bambamboole\Framework\Container\Container;
use Bambamboole\Framework\Container\ContainerInterface;

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

    public function bind(string $key, string|\Closure $implementation): void
    {
        $this->container->bind($key, $implementation);
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
        date_default_timezone_set($this->config->get('app.timezone', 'UTC'));
    }
}
