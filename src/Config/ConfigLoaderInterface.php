<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Config;

use Bambamboole\Framework\Environment\Environment;

interface ConfigLoaderInterface
{
    public function load(Environment $env): Config;
}
