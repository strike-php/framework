<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Exception;

use Symfony\Component\HttpFoundation\Response;

interface ExceptionHandlerInterface
{
    public function handleException(\Throwable $e): Response;
}
