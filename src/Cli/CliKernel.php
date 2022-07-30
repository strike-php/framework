<?php

declare(strict_types=1);

namespace Strike\Framework\Cli;

use Strike\Framework\Core\ApplicationInterface;
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

        return $cli->run($input, $output);
    }
}
