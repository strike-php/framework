<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Exception;

use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler implements ExceptionHandlerInterface
{
    public function __construct(
        private readonly bool $isDebugEnabled,
    ) {
    }

    public function handleException(\Throwable $e): Response
    {
        $renderer = new HtmlErrorRenderer($this->isDebugEnabled);

        $content = $renderer
            ->render($e)
            ->getAsString();

        return new Response($content, 500);
    }
}
