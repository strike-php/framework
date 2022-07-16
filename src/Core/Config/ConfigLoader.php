<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

use Strike\Framework\Core\Environment\Environment;
use Strike\Framework\Core\Filesystem\Filesystem;

class ConfigLoader
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
    ) {
    }

    public function load(string $configFilesPath, Environment $env): ConfigInterface
    {
        $config = new Config();

        foreach ($this->prepareConfigFiles($configFilesPath) as $key => $path) {
            $config->set($key, require $path);
        }

        return $config;
    }

    private function prepareConfigFiles($path): array
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path, '.php') as $file) {
            $prefix = $this->generatePrefix($file, $path);
            $configName = \basename($file->getRealPath(), '.php');
            $files[$prefix . $configName] = $file->getRealPath();
        }

        \ksort($files, SORT_NATURAL);

        return $files;
    }

    private function generatePrefix(\SplFileInfo $file, string $path): string
    {
        $prefix = \trim(\str_replace($path, '', $file->getPath()), DIRECTORY_SEPARATOR);

        if ($prefix !== '') {
            $prefix = \str_replace(DIRECTORY_SEPARATOR, '.', $prefix) . '.';
        }

        return $prefix;
    }
}
