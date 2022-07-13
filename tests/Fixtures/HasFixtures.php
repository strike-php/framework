<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Fixtures;

trait HasFixtures
{
    protected function getFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__, $path);
    }

    protected function getEnvironmentFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/environment', $path);
    }

    protected function getFilesystemFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/filesystem', $path);
    }

    protected function getConfigFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/config', $path);
    }

    protected function getRoutingFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/routing', $path);
    }

    protected function getBootstrapCacheFixturesPath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/bootstrap/cache', $path);
    }

    private function concatPath($base, ?string $path = null): string
    {
        return empty($path)
            ? $base
            : $base . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }
}
