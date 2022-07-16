<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Container;

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
            if (\interface_exists($key)) {
                throw new \Exception('No implementation defined for interface: ' . $key);
            }
            throw new \Exception('Class does not exist: ' . $key);
        }

        return $this->build($key);
    }

    private function build(string $key): mixed
    {
        $reflector = new \ReflectionClass($key);

        $constructor = $reflector->getConstructor();
        if (\is_null($constructor)) {
            return new $key();
        }

        $dependencies = \array_map(
            fn (\ReflectionParameter $parameter) => $this->resolveParameter($parameter),
            $constructor->getParameters(),
        );

        return $reflector->newInstanceArgs($dependencies);
    }

    private function resolveParameter(\ReflectionParameter $parameter): mixed
    {
        if ($parameter->getType() instanceof \ReflectionNamedType) {
            return $this->make($parameter->getType()->getName());
        }

        return '';
    }
}
