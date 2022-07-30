<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Cli\CliCommandRegistry;
use Strike\Framework\Cli\CliModule;
use Strike\Framework\Cli\StrikeCli;
use Strike\Framework\Core\ApplicationInterface;

class CliModuleTest extends TestCase
{
    public function testItBindsTheExpectedBindings(): void
    {
        $expectedBindings = [CliCommandRegistry::class, StrikeCli::class];

        $app = $this->createMock(ApplicationInterface::class);
        $app
            ->expects(self::exactly(\count($expectedBindings)))
            ->method('singleton')
            ->withConsecutive(...\array_map(fn (string $class) => [$class], $expectedBindings));

        $module = new CliModule($app);

        $module->register();
    }
}
