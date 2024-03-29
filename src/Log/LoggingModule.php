<?php

declare(strict_types=1);

namespace Strike\Framework\Log;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\ModuleInterface;
use Psr\Log\LoggerInterface;

class LoggingModule implements ModuleInterface
{
    public function __construct(
        private readonly ApplicationInterface $app,
    ) {
    }

    public function register(): void
    {
        $this->app->bind(
            LoggerFactoryInterface::class,
            fn (ContainerInterface $container) => $container->get(LoggerFactory::class),
            true,
        );
        $this->app->bind(
            LoggerInterface::class,
            fn (ContainerInterface $container) => $container->get(LoggerFactoryInterface::class)->createDefaultLogger(),
            true,
        );
    }

    public function load(): void
    {
    }
}
