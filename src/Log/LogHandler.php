<?php

namespace Bambamboole\Framework\Log;

use Bambamboole\Framework\Core\Config\ConfigInterface;
use Bambamboole\Framework\Log\Exception\LogChannelConfigurationException;
use Bambamboole\Framework\Log\Exception\NotExistingLogChannelException;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogHandler implements LogHandlerInterface, LoggerInterface
{
    private array $loggers = [];

    public function __construct(
        private readonly ConfigInterface $config,
    )
    {
    }

    public function createLogger(string $name, ?array $config = []): LoggerInterface
    {
        if (!array_key_exists($name, $this->loggers)) {
            $this->loggers[$name] = new Logger($name, [$this->createMonologHandler($name, $config)]);
        }

        return $this->loggers[$name];
    }

    private function createDefaultLogger(): LoggerInterface
    {
        $channel = $this->getDefaultChannel();

        return $this->createLogger(
            $channel,
            $this->config->get("logging.channels.{$channel}", [])
        );
    }

    private function getDefaultChannel(): string
    {
        return $this->config->get('logging.default', 'file');
    }


    private function createMonologHandler($name, array $config): HandlerInterface
    {
        return match ($name) {
            'single' => $this->createSingleFileStreamHandler($config),
            default => throw new NotExistingLogChannelException(
                sprintf('Log channel "%s" not configured', $name)
            ),
        };
    }

    private function createSingleFileStreamHandler(array $config): HandlerInterface
    {
        $path = $config['path'] ?? $this->config->get('app.base_path');
        if (is_null($path)) {
            throw new LogChannelConfigurationException('No path defined for single log driver');
        }

        return new StreamHandler(
            $path,
            $this->getLogLevel($config['level']),
            true,
        );
    }

    private function getLogLevel(LogLevel|string|null $level = null): Level
    {
        if (is_null($level)) {
            return Level::Debug;
        }

        return Level::fromName($level);
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->emergency($message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->alert($message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->critical($message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->error($message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->warning($message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->notice($message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->info($message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->debug($message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->createDefaultLogger()->log($level, $message, $context);
    }
}
