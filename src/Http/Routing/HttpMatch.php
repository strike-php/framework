<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

class HttpMatch
{
    public function __construct(private readonly string $handler, private readonly array $params = [])
    {
    }

    public static function fromSymfonyUrlMatcherResult(array $urlMatcherResult): self
    {
        return new self(
            $urlMatcherResult['_controller'],
            \array_filter($urlMatcherResult, fn ($key) => !\str_starts_with($key, '_'), ARRAY_FILTER_USE_KEY),
        );
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getParams(?string $key = null): mixed
    {
        return $key
            ? $this->params[$key]
            : $this->params;
    }
}
