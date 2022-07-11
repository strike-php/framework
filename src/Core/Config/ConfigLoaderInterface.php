<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Core\Config;

use Bambamboole\Framework\Core\Environment\Environment;

interface ConfigLoaderInterface
{
    public function load(Environment $env): Config;
}
