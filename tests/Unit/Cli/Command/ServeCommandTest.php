<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli\Command;

use Monolog\Test\TestCase;
use Strike\Framework\Cli\Commands\ServeCommand;
use Strike\Framework\Cli\Factory\ProcessFactory;
use Strike\Framework\Core\ApplicationInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ServeCommandTest extends TestCase
{
    public function testItResolvesTheCorrectArguments(): void
    {
        $app = $this->createMock(ApplicationInterface::class);
        $processFactory = $this->createMock(ProcessFactory::class);
        $phpExecutableFinder = $this->createMock(PhpExecutableFinder::class);
        $process = $this->createMock(Process::class);
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects(self::once())
            ->method('getOption')
            ->with('port')
            ->willReturn('8080');
        $app
            ->expects(self::once())
            ->method('getDocumentRoot')
            ->willReturn('/var/www/html/public');
        $phpExecutableFinder
            ->expects(self::once())
            ->method('find')
            ->willReturn('/usr/bin/php');
        $processFactory
            ->expects(self::once())
            ->method('getInstance')
            ->with(['/usr/bin/php', '-S', '127.0.0.1:8080', '-t', '/var/www/html/public'])
            ->willReturn($process);
        $process
            ->expects(self::once())
            ->method('run');

        $command = new ServeCommand($app, $processFactory, $phpExecutableFinder);

        $command->execute(
            $input,
            $this->createMock(OutputInterface::class),
        );
    }
}
