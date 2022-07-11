<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Core\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function bind(string $key, string|\Closure $implementation, bool $isShared = false): void;
}
