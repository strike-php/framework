<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

class HttpMatch
{
    public function __construct(
        private readonly string $handler,
        private readonly array $params = [],
        private readonly array $middleware = [],
    ) {
    }

    public static function fromSymfonyUrlMatcherResult(array $urlMatcherResult): self
    {
        return new self(
            $urlMatcherResult['_controller'],
            \array_filter($urlMatcherResult, fn ($key) => !\str_starts_with($key, '_'), ARRAY_FILTER_USE_KEY),
            $urlMatcherResult['_middleware'],
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

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
