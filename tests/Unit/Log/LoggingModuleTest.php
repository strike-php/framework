<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Log;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Log\LoggerFactoryInterface;
use Strike\Framework\Log\LoggingModule;

class LoggingModuleTest extends TestCase
{
    private MockObject|ApplicationInterface $app;

    protected function setUp(): void
    {
        $this->app = $this->createMock(ApplicationInterface::class);
    }

    public function testItBindsTheLoggingFactoryInterface(): void
    {
        $module = $this->createLoggingModule();
        $this->app
            ->expects(self::exactly(2))
            ->method('bind')
            ->withConsecutive([LoggerFactoryInterface::class], [LoggerInterface::class]);

        $module->register();
    }

    private function createLoggingModule(): LoggingModule
    {
        return new LoggingModule($this->app);
    }
}
