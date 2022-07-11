<?php

namespace Bambamboole\Framework\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function bind(string $key, string|\Closure $implementation): void;
}
