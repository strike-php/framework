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
            fn (ContainerInterface $container) => $container->get(CliCommandRegistry::class),
        );

        $this->application->singleton(
            StrikeCli::class,
            function (ContainerInterface $container) {
                $cli = new StrikeCli();
                $registry = $container->get(CliCommandRegistry::class);
                foreach ($container->get(ApplicationInterface::class)->getRegisteredCommands() as $command => $factory) {
                    $registry->add($command, $factory);
                }
                $cli->setCommandLoader($container->get(CliCommandRegistry::class));

                return $cli;
            },
        );
    }

    public function load(): void
    {
        // TODO: Implement load() method.
    }
}
