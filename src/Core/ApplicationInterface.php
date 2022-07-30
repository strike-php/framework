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

    public function registerModule(string $moduleClass): void;

    public function runningInConsole(): bool;

    public function registerCommand(string $command, ?\Closure $factory = null): void;

    public function getRegisteredCommands(): array;

    public function boot(): void;
}
