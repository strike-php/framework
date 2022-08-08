<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

interface ApplicationPathsInterface
{
    public function getBasePath(?string $path = null): string;

    public function getConfigPath(): string;

    public function getRoutesPath(): string;

    public function getCachedConfigPath(): string;

    public function getCachedRoutesPath(): string;

    public function getDocumentRoot(): string;
}
