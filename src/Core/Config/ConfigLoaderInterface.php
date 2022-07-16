<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

use Strike\Framework\Core\Environment\Environment;

interface ConfigLoaderInterface
{
    public function load(Environment $env): Config;
}
