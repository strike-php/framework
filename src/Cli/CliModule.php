<?php

declare(strict_types=1);

namespace Strike\Framework\Cli;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\ModuleInterface;

class CliModule implements ModuleInterface
{
    public function __construct(private readonly ApplicationInterface $application)
    {
    }

    public function register(): void
    {
        $this->application->singleton(
            CliCommandRegistry::class,
            fn (ContainerInterface $container) => new CliCommandRegistry($container),
        );

        $this->application->singleton(StrikeCli::class, fn () => new StrikeCli());
    }

    public function load(): void
    {
    }
}
