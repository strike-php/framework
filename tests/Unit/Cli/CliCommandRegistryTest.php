<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Strike\Framework\Cli\CliCommandRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class CliCommandRegistryTest extends TestCase
{
    public function testItCreatesAFactoryClosureWhichResolvesViaTheContainerIfNotProvided(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects(self::once())
            ->method('get')
            ->with(TestCommand::class)
            ->willReturn(new TestCommand());
        $registry = new CliCommandRegistry($container);

        $registry->add(TestCommand::class);
        $command = $registry->get('test');

        self::assertInstanceOf(TestCommand::class, $command);
    }

    public function testItCanReturnAllCommandNames(): void
    {
        $registry = new CliCommandRegistry($this->createMock(ContainerInterface::class));

        $registry->add(TestCommand::class);

        self::assertEquals(['test'], $registry->getNames());
    }

    public function testItThrowsAnExceptionIfANonRegisteredCommandWillBeResolved(): void
    {
        $registry = new CliCommandRegistry($this->createMock(ContainerInterface::class));

        self::expectException(CommandNotFoundException::class);

        $registry->get('foo');
    }
}

#[AsCommand('test')]
class TestCommand extends Command
{
}
