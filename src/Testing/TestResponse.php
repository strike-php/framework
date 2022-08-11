<?php

declare(strict_types=1);

namespace Strike\Framework\Testing;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

class TestResponse
{
    public function __construct(public readonly Response $response)
    {
    }

    public function assertBody(string $content): self
    {
        Assert::assertEquals($content, $this->response->getContent());

        return $this;
    }

    public function assertBodyContains(string $content): self
    {
        Assert::assertStringContainsString($content, $this->response->getContent());

        return $this;
    }
}
