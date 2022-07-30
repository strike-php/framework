<?php

declare(strict_types=1);

namespace Strike\Framework\Cli;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\ConfigInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliKernel
{
    public function __construct(private readonly ApplicationInterface $app)
    {
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->app->boot();

        /** @var StrikeCli $cli */
        $cli = $this->app->get(StrikeCli::class);
        $registry = $this->app->get(CliCommandRegistry::class);
        foreach ($this->getCommands() as $command) {
            $registry->add($command);
        }
        $cli->setCommandLoader($registry);

        return $cli->run($input, $output);
    }

    private function getCommands(): array
    {
        return $this->app->get(ConfigInterface::class)->get('cli.commands', []);
    }
}
