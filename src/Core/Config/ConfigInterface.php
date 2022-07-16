<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

interface ConfigInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function all(): array;
}
