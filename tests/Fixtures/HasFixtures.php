<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Fixtures;

trait HasFixtures
{
    protected function getFixturePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__, $path);
    }

    protected function getTestingApplicationBasePath(?string $path = null): string
    {
        return $this->concatPath(__DIR__ . '/Application', $path);
    }

    protected function getFilesystemFixturePath(?string $path = null): string
    {
        return $this->concatPath($this->getTestingApplicationBasePath('var/storage'), $path);
    }

    protected function getConfigFixturePath(): string
    {
        return $this->getTestingApplicationBasePath('/etc/config');
    }

    protected function getCacheFixturesPath(?string $path = null): string
    {
        return $this->concatPath($this->getTestingApplicationBasePath('var/cache'), $path);
    }

    private function concatPath($base, ?string $path = null): string
    {
        return empty($path)
            ? $base
            : $base . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
    }
}
