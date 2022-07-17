<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core\Exception;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\Exception\ExceptionHandler;
use Symfony\Component\String\UnicodeString;

class ExceptionHandlerTest extends TestCase
{
    public function testItRendersExceptionMessageIntoTheTitleIfDebugIsEnabled(): void
    {
        $handler = new ExceptionHandler(true);

        $response = $handler->handleException(new \Exception('test-exception'));

        self::assertStringContainsString('test-exception', $this->getTitle($response->getContent()));
    }

    public function testItRendersNoExceptionMessageIntoTheTitleIfDebugIsDisabled(): void
    {
        $handler = new ExceptionHandler(false);

        $response = $handler->handleException(new \Exception('test-exception'));

        self::assertStringNotContainsString('test-exception', $this->getTitle($response->getContent()));
    }

    private function getTitle(string $document): string
    {
        $content = new UnicodeString($document);

        return $content->match('/<title[^>]*>(.*?)<\/title>/ims')[1];
    }
}
