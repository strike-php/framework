<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Log;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Log\Exception\LogChannelConfigurationException;
use Strike\Framework\Log\Exception\NotExistingLogChannelException;
use Strike\Framework\Log\LoggerFactory;
use Tests\Strike\Framework\Unit\HelpsWithClosedClasses;

class LoggerFactoryTest extends TestCase
{
    use HelpsWithClosedClasses;

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

        $this->createLoggerFactory()->createLogger('single', []);
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

    public function testTheDailyDriverIsBasedOnTheRotatingFileHandler(): void
    {
        $logger = $this->createLoggerFactory()->createLogger('daily', ['path' => __DIR__]);

        self::assertInstanceOf(Logger::class, $logger);
        self::assertInstanceOf(RotatingFileHandler::class, $handler = $logger->popHandler());
        // it defaults to 7 days which are the value of the maxFiles property
        self::assertEquals(7, $this->getNonPublicProperty($handler, 'maxFiles'));
    }

    public function testNumberOfMaxFilesAreControlledViaTheDaysConfigProperty(): void
    {
        $logger = $this->createLoggerFactory()->createLogger('daily', ['path' => __DIR__, 'days' => 2]);

        self::assertEquals(2, $this->getNonPublicProperty($logger->popHandler(), 'maxFiles'));
    }

    public function testItThrowsAnExceptionIfNoPathIsDefinedForDailyLogDriver(): void
    {
        self::expectException(LogChannelConfigurationException::class);

        $this->createLoggerFactory()->createLogger('daily', []);
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
