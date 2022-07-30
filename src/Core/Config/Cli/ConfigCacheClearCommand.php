<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config\Cli;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('config:clear', 'Clear the config cache')]
class ConfigCacheClearCommand extends Command
{
    public function __construct(
        private readonly ApplicationInterface $app,
        private readonly Filesystem $filesystem,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filesystem->remove($this->app->getCachedConfigPath());

        $output->writeln('Config cache successfully cleared');

        return 0;
    }
}
