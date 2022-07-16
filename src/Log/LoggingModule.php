<?php

namespace Bambamboole\Framework\Log;

use Bambamboole\Framework\Core\Application;
use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Core\Container\ContainerInterface;
use Bambamboole\Framework\Core\ModuleInterface;
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
