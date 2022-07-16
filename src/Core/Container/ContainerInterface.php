<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function bind(string $key, string|\Closure $implementation, bool $isShared = false): void;

    public function instance(string $key, mixed $instance): void;
}
