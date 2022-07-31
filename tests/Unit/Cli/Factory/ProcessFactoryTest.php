<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli\Factory;

use Monolog\Test\TestCase;
use Strike\Framework\Cli\Factory\ProcessFactory;

class ProcessFactoryTest extends TestCase
{
    public function testItPassesTheArguments(): void
    {
        $factory = new ProcessFactory();

        $process = $factory->getInstance(['ls', '-la']);

        self::assertEquals("'ls' '-la'", $process->getCommandLine());
    }

    public function testTheWorkingDirectoryIsConfigurable(): void
    {
        $factory = new ProcessFactory();

        $process = $factory->getInstance(['ls', '-la'], '/tmp');

        self::assertEquals('/tmp', $process->getWorkingDirectory());
    }
}
