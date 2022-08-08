<?php

declare(strict_types=1);

namespace Strike\Framework\Cli\Commands;

use Strike\Framework\Cli\Factory\ProcessFactory;
use Strike\Framework\Core\ApplicationInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

#[AsCommand('serve', 'Starts a simple PHP web server to kickstart development')]
class ServeCommand extends Command
{
    public function __construct(
        private readonly ApplicationInterface $application,
        private readonly ProcessFactory $processFactory,
        private readonly PhpExecutableFinder $phpExecutableFinder,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption(
            'port',
            'p',
            InputOption::VALUE_REQUIRED,
            'Set the port used by the webserver',
            8080,
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = $this
            ->processFactory
            ->getInstance($this->getArguments($input));

        $process->start(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });

        while ($process->isRunning()) {
            \usleep(300 * 1000);
        }

        return 0;
    }

    private function getArguments(InputInterface $input): array
    {
        return [
            $this->phpExecutableFinder->find(),
            '-S', \implode(':', ['127.0.0.1', $input->getOption('port')]),
            '-t', $this->application->getDocumentRoot(),
        ];
    }
}
