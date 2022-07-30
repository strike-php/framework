<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

use Strike\Framework\Core\Config\ConfigInterface;

class ModuleBootstrapper implements BootstrapperInterface
{
    public function __construct(private readonly ConfigInterface $config)
    {
    }

    public function bootstrap(ApplicationInterface $application): void
    {
        $moduleClasses = $this->config->get('app.modules', []);

        foreach ($moduleClasses as $moduleClass) {
            $application->registerModule($moduleClass);
        }
    }
}
