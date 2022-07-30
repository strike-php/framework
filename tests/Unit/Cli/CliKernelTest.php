<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Cli\CliKernel;
use Strike\Framework\Cli\StrikeCli;
use Strike\Framework\Core\ApplicationInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliKernelTest extends TestCase
{
    public function testItBootsTheApplicationAndExecutesStrikeCliWithThePassedArguments(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        $cli = $this->createMock(StrikeCli::class);
        $app = $this->createMock(ApplicationInterface::class);

        $app
            ->expects(self::once())
            ->method('boot');
        $app
            ->expects(self::once())
            ->method('get')
            ->with(StrikeCli::class)
            ->willReturn($cli);
        $cli
            ->expects(self::once())
            ->method('run')
            ->with($input, $output)
            ->willReturn(0);

        $kernel = new CliKernel($app);
        $status = $kernel->handle($input, $output);

        self::assertEquals(0, $status);
    }
}
