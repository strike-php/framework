<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Cli\CliCommandRegistry;
use Strike\Framework\Cli\CliKernel;
use Strike\Framework\Cli\StrikeCli;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\Config;
use Strike\Framework\Core\Config\ConfigInterface;
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
        $registry = $this->createMock(CliCommandRegistry::class);
        $app
            ->expects(self::once())
            ->method('boot');
        $app
            ->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([StrikeCli::class], [CliCommandRegistry::class], [ConfigInterface::class])
            ->willReturn($cli, $registry, new Config(['cli' => ['commands' => ['test']]]));
        $registry
            ->expects(self::once())
            ->method('add')
            ->with('test');
        $cli
            ->expects(self::once())
            ->method('setCommandLoader')
            ->with($registry);
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
