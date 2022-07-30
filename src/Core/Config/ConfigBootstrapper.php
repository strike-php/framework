<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\BootstrapperInterface;

class ConfigBootstrapper implements BootstrapperInterface
{
    private array $additionalDotEnvFiles = [];

    public function addDotEnvFile(string $filePath): self
    {
        $this->additionalDotEnvFiles[] = new \SplFileInfo($filePath);

        return $this;
    }

    public function bootstrap(ApplicationInterface $application): void
    {
        $config = $application->get(ConfigFactory::class)->create(
            $application->getConfigPath(),
            $application->getCachedConfigPath(),
            \array_merge([new \SplFileInfo($application->getBasePath('.env'))], $this->additionalDotEnvFiles),
        );
        \date_default_timezone_set($config->get('app.timezone', 'UTC'));
        $application->instance(ConfigInterface::class, $config);
    }
}
