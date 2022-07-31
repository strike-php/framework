<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Strike\Framework\Core\Container\ContainerInterface;

interface ApplicationInterface extends ContainerInterface
{
    public function getBasePath(?string $path = null): string;

    public function getConfigPath(): string;

    public function getCachedConfigPath(): string;

    public function getRoutesPath(): string;

    public function getCachedRoutesPath(): string;

    public function getDocumentRoot(): string;

    public function registerModule(string $moduleClass): void;

    public function boot(): void;
}
