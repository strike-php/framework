<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Filesystem;

class Filesystem
{
    public function __construct(
        private readonly FinderFactory $finderFactory = new FinderFactory(),
    ) {
    }

    public function exists(string $filePath): bool
    {
        return \file_exists($filePath);
    }

    public function put(string $path, string $content): bool
    {
        return !(\file_put_contents($path, $content) === false);
    }

    public function get(string $path): string
    {
        return \file_get_contents($path);
    }

    public function remove(string $path): void
    {
        \unlink($path);
    }

    /** @return \SplFileInfo[] */
    public function allFiles(string $path, ?string $suffix = null, bool $ignoreHidden = true): array
    {
        $finder = $this->finderFactory
            ->create()
            ->files()
            ->in($path)
            ->sortByName();

        if ($ignoreHidden === false) {
            $finder->ignoreDotFiles(false);
        }

        if ($suffix) {
            $finder->name('*.' . \ltrim($suffix, '*.'));
        }

        return \iterator_to_array($finder, false);
    }
}
