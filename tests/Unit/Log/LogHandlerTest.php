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
use Strike\Framework\Log\LogHandler;

class LogHandlerTest extends TestCase
{
    private ConfigInterface|MockObject $config;
    private LogHandler $logHandler;

    protected function setUp(): void
    {
        $this->config = $this->createMock(ConfigInterface::class);
        $this->logHandler = new LogHandler($this->config);
    }

    public function testItThrowsAnExceptionIfTheDriverIsNotSupported(): void
    {
        self::expectException(NotExistingLogChannelException::class);

        $this->logHandler->createLogger('foo');
    }

    public function testItThrowsAnExceptionIfNoPathIsDefinedForSingleLogDriver(): void
    {
        self::expectException(LogChannelConfigurationException::class);

        $this->logHandler->createLogger('single', ['path' => null]);
    }

    public function testTheLogLevelCanBeConfigured(): void
    {
        $logger = $this->logHandler->createLogger('single', ['path' => __DIR__, 'level' => 'error']);

        self::assertInstanceOf(Logger::class, $logger);
        $streamHandler = $logger->getHandlers()[0];
        self::assertInstanceOf(StreamHandler::class, $streamHandler);
        self::assertEquals('ERROR', $streamHandler->getLevel()->getName());
    }

    public function testTheLogLevelDefaultsToDebug(): void
    {
        $logger = $this->logHandler->createLogger('single', ['path' => __DIR__]);

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

        $logger = $this->logHandler->createDefaultLogger();

        self::assertInstanceOf(Logger::class, $logger);
    }
}
