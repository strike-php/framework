<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Container;

class Binding
{
    public function __construct(
        private readonly string $key,
        private readonly \Closure $implementation,
        private readonly bool $shared,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getImplementation(): \Closure
    {
        return $this->implementation;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }
}
