<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Core;

use Bambamboole\Framework\Core\Container\ContainerInterface;

interface ModuleInterface
{
    public function load(ContainerInterface $container): void;
}
