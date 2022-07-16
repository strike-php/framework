<?php

namespace Bambamboole\Framework\Log;

use Psr\Log\LoggerInterface;

interface LogHandlerInterface
{
    public function createLogger(string $name, ?array $config = []): LoggerInterface;
}
