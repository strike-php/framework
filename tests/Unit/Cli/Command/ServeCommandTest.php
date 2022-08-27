<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli\Command;

use Monolog\Test\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Strike\Framework\Cli\Commands\ServeCommand;
use Strike\Framework\Cli\Factory\ProcessFactory;
use Strike\Framework\Core\ApplicationInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ServeCommandTest extends TestCase
{
    private MockObject|ApplicationInterface $app;
    private MockObject|ProcessFactory $processFactory;
    private MockObject|PhpExecutableFinder $phpExecutableFinder;

    protected function setUp(): void
    {
        $this->app = $this->createMock(ApplicationInterface::class);
        $this->processFactory = $this->createMock(ProcessFactory::class);
        $this->phpExecutableFinder = $this->createMock(PhpExecutableFinder::class);
    }

    public function testItResolvesTheCorrectArguments(): void
    {
        $process = $this->createMock(Process::class);
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects(self::once())
            ->method('getOption')
            ->with('port')
            ->willReturn('8080');
        $this->app
            ->expects(self::once())
            ->method('getDocumentRoot')
            ->willReturn('/var/www/html/public');
        $this->phpExecutableFinder
            ->expects(self::once())
            ->method('find')
            ->willReturn('/usr/bin/php');
        $this->processFactory
            ->expects(self::once())
            ->method('getInstance')
            ->with(['/usr/bin/php', '-S', '127.0.0.1:8080', '-t', '/var/www/html/public'])
            ->willReturn($process);
        $process
            ->expects(self::once())
            ->method('start');
        // We break out of the while loop via returning false from the isRunning method.
        $process
            ->expects(self::once())
            ->method('isRunning')
            ->willReturn(false);

        $command = $this->createServeCommand();

        $statusCode = $command->execute(
            $input,
            $this->createMock(OutputInterface::class),
        );

        self::assertEquals(0, $statusCode);
    }

    public function testAPortOptionIsDeclared(): void
    {
        $command = $this->createServeCommand();

        self::assertTrue($command->getDefinition()->hasOption('port'));
    }

    private function createServeCommand(): ServeCommand
    {
        return new ServeCommand($this->app, $this->processFactory, $this->phpExecutableFinder);
    }
}
