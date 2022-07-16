<?php

declare(strict_types=1);

namespace Strike\Framework\Log;

use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Log\Exception\LogChannelConfigurationException;
use Strike\Framework\Log\Exception\NotExistingLogChannelException;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogHandler implements LogHandlerInterface
{
    private array $loggers = [];

    public function __construct(
        private readonly ConfigInterface $config,
    ) {
    }

    public function createLogger(string $name, ?array $config = []): LoggerInterface
    {
        if (!\array_key_exists($name, $this->loggers)) {
            $this->loggers[$name] = new Logger($name, [$this->createMonologHandler($name, $config)]);
        }

        return $this->loggers[$name];
    }

    public function createDefaultLogger(): LoggerInterface
    {
        $channel = $this->getDefaultChannel();

        return $this->createLogger(
            $channel,
            $this->config->get("logging.channels.{$channel}", []),
        );
    }

    private function getDefaultChannel(): string
    {
        return $this->config->get('logging.default', 'single');
    }

    private function createMonologHandler($name, array $config): HandlerInterface
    {
        return match ($name) {
            'single' => $this->createSingleFileStreamHandler($config),
            default => throw new NotExistingLogChannelException(
                \sprintf('Log channel "%s" not configured', $name),
            ),
        };
    }

    private function createSingleFileStreamHandler(array $config): HandlerInterface
    {
        $path = $config['path'] ?? $this->config->get('app.base_path');
        if (\is_null($path)) {
            throw new LogChannelConfigurationException('No path defined for single log driver');
        }

        return new StreamHandler(
            $path,
            $this->getLogLevel($config['level'] ?? null),
            true,
        );
    }

    private function getLogLevel(LogLevel|string|null $level = null): Level
    {
        if (\is_null($level)) {
            return Level::Debug;
        }

        return Level::fromName($level);
    }
}
