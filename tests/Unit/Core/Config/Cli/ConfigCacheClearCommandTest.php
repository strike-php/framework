<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core\Config\Cli;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\Cli\ConfigCacheClearCommand;
use Strike\Framework\Core\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCacheClearCommandTest extends TestCase
{
    public function testItUsesTheApplicationTOGetTHeCorrectPathToDelete(): void
    {
        $app = $this->createMock(ApplicationInterface::class);
        $filesystem = $this->createMock(Filesystem::class);
        $app
            ->expects(self::once())
            ->method('getCachedConfigPath')
            ->willReturn('/test/config');
        $filesystem
            ->expects(self::once())
            ->method('remove')
            ->with('/test/config');

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects(self::once())
            ->method('writeln')
            ->with('Config cache successfully cleared');

        $command = new ConfigCacheClearCommand($app, $filesystem);
        $status = $command->execute($this->createMock(InputInterface::class), $output);

        self::assertEquals(0, $status);
    }
}
