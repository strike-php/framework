<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http\Middleware;

class MiddlewareException extends \Exception
{
    public static function notImplemented(object $class): self
    {
        return new self(
            \sprintf(
                '"%s" does not implement %s',
                \get_class($class),
                MiddlewareInterface::class,
            ),
        );
    }
}
