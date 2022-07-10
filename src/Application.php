<?php

declare(strict_types=1);

namespace Bambamboole\Framework;

use Bambamboole\Framework\Config\Config;

class Application
{
    public function __construct(
        private readonly string $basePath,
        private Config $config,
    ) {
    }

    public function getBasePath(?string $path = null): string
    {
        return empty($path)
            ? $this->basePath
            : $this->basePath . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }
}
