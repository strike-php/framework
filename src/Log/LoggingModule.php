<?php

namespace Strike\Framework\Log;

use Strike\Framework\Core\Application;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\ModuleInterface;
use Psr\Log\LoggerInterface;

class LoggingModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app,
    )
    {
    }

    public function register(): void
    {
        $this->app->bind(
            LogHandlerInterface::class,
            fn (ContainerInterface $container) => $container->get(LogHandler::class),
            true,
        );
        $this->app->bind(
            LoggerInterface::class,
            fn (ContainerInterface $container) => $container->get(LogHandlerInterface::class)->createDefaultLogger(),
            true,
        );
    }

    public function load(): void
    {
    }
}
