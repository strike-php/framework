<?php

namespace Bambamboole\Framework\Config;

use Bambamboole\Framework\Environment\Environment;
use Bambamboole\Framework\Filesystem\Filesystem;

class ConfigLoader
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
    )
    {
    }

    public function load(string $configPath, string $cachedConfigPath, Environment $env): Config
    {
        if ($this->filesystem->exists($cachedConfigPath)) {
            return new Config(require $cachedConfigPath);
        }
        $config = new Config();

        foreach ($this->prepareConfigFiles($configPath) as $key => $path) {
            $config->set($key, require $path);
        }

        $this->dumpConfig($cachedConfigPath, $config->all());

        return $config;
    }

    private function prepareConfigFiles($path): array
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path, '.php') as $file) {
            $prefix = $this->generatePrefix($file, $path);
            $configName = basename($file->getRealPath(), '.php');
            $files[$prefix . $configName] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    private function generatePrefix(\SplFileInfo $file, string $path): string
    {
        $prefix = trim(str_replace($path, '', $file->getPath()), DIRECTORY_SEPARATOR);

        if ($prefix !== '') {
            $prefix = str_replace(DIRECTORY_SEPARATOR, '.', $prefix) . '.';
        }

        return $prefix;
    }

    private function dumpConfig(string $path, array $config): void
    {
        $this->filesystem->put(
            $path,
            '<?php return ' . var_export($config, true) . ';' . PHP_EOL
        );
    }
}
