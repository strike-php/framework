<?php

declare(strict_types=1);

namespace Strike\Framework\Log;

use Psr\Log\LoggerInterface;

interface LogHandlerInterface
{
    public function createLogger(string $name, ?array $config = []): LoggerInterface;

    public function createDefaultLogger(): LoggerInterface;
}
