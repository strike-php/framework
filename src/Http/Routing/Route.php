<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

class Route
{
    public function __construct(
        private readonly HttpMethod $httpMethod,
        private readonly string $path,
        private readonly string $controller,
        private string $name = '',
    ) {
    }

    public function getHttpMethod(): HttpMethod
    {
        return $this->httpMethod;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
