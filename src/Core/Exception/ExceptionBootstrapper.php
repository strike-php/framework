<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Exception;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\BootstrapperInterface;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Container\ContainerInterface;

class ExceptionBootstrapper implements BootstrapperInterface
{
    public function bootstrap(ApplicationInterface $application): void
    {
        $application->singleton(
            ExceptionHandlerInterface::class,
            fn (ContainerInterface $container) => new ExceptionHandler(
                $container->get(ConfigInterface::class)->get('app.debug', false),
            ),
        );
    }
}
