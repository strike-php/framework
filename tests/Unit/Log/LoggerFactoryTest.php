<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Log\Exception\LogChannelConfigurationException;
use Strike\Framework\Log\Exception\NotExistingLogChannelException;
use Strike\Framework\Log\LoggerFactory;

class LoggerFactoryTest extends TestCase
{
    private ConfigInterface|MockObject $config;

    protected function setUp(): void
    {
        $this->config = $this->createMock(ConfigInterface::class);
    }

    public function testItThrowsAnExceptionIfTheDriverIsNotSupported(): void
    {
        self::expectException(NotExistingLogChannelException::class);

        $this->createLoggerFactory()->createLogger('foo');
    }

    public function testItThrowsAnExceptionIfNoPathIsDefinedForSingleLogDriver(): void
    {
        self::expectException(LogChannelConfigurationException::class);

        $this->createLoggerFactory()->createLogger('single', ['path' => null]);
    }

    public function testTheLogLevelCanBeConfigured(): void
    {
        $logger = $this->createLoggerFactory()->createLogger('single', ['path' => __DIR__, 'level' => 'error']);

        self::assertInstanceOf(Logger::class, $logger);
        $streamHandler = $logger->getHandlers()[0];
        self::assertInstanceOf(StreamHandler::class, $streamHandler);
        self::assertEquals('ERROR', $streamHandler->getLevel()->getName());
    }

    public function testTheLogLevelDefaultsToDebug(): void
    {
        $logger = $this->createLoggerFactory()->createLogger('single', ['path' => __DIR__]);

        self::assertInstanceOf(Logger::class, $logger);
        $streamHandler = $logger->getHandlers()[0];
        self::assertInstanceOf(StreamHandler::class, $streamHandler);
        self::assertEquals('DEBUG', $streamHandler->getLevel()->getName());
    }

    public function testItCanCreateTheDefaultLogger(): void
    {
        $this->config
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(
                ['logging.default', 'single'],
                ['logging.channels.single', []],
            )
            ->willReturnOnConsecutiveCalls('single', ['path' => __DIR__]);

        $logger = $this->createLoggerFactory()->createDefaultLogger();

        self::assertInstanceOf(Logger::class, $logger);
    }

    private function createLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory($this->config);
    }
}
