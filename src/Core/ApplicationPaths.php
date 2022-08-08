<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

class ApplicationPaths implements ApplicationPathsInterface
{
    public function __construct(private readonly string $basePath)
    {
    }

    public function getBasePath(?string $path = null): string
    {
        return empty($path)
            ? $this->basePath
            : \rtrim($this->basePath, '/\\'). DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }

    public function getConfigPath(): string
    {
        return $this->getBasePath('etc/config');
    }

    public function getRoutesPath(): string
    {
        return $this->getBasePath('etc/routes.php');
    }

    public function getCachedConfigPath(): string
    {
        return $this->getBasePath('var/cache/cached-config.php');
    }

    public function getCachedRoutesPath(): string
    {
        return $this->getBasePath('var/cache/cached-routes.php');
    }

    public function getDocumentRoot(): string
    {
        return $this->getBasePath('public');
    }
}
