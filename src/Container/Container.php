<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /** @var array<string, Binding> */
    private array $bindings = [];

    private array $instances = [];

    public function get(string $id)
    {
        return $this->make($id);
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function bind(string $key, string|\Closure $implementation, bool $isShared = false): void
    {
        if (\is_string($implementation)) {
            $implementation = fn (self $container) => $container->get($implementation);
        }
        $binding = new Binding($key, $implementation, $isShared);
        $this->bindings[$binding->getKey()] = $binding;
    }

    public function instance(string $key, mixed $instance): void
    {
        $this->instances[$key] = $instance;
    }

    public function resolved(string $key): bool
    {
        return isset($this->instances[$key]);
    }

    public function make(string $key): mixed
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (isset($this->bindings[$key])) {
            $binding = $this->bindings[$key];
            $instance = \call_user_func($binding->getImplementation(), $this);
            if ($binding->isShared()) {
                $this->instances[$key] = $instance;
            }

            return $instance;
        }

        if (!\class_exists($key)) {
            throw new \Exception('class does not exist');
        }

        return new $key();
    }
}
