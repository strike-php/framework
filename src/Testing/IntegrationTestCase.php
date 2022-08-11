<?php

declare(strict_types=1);

namespace Strike\Framework\Testing;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Exception\ExceptionHandlerInterface;
use Strike\Framework\Http\HttpKernel;
use Strike\Framework\Http\Routing\HttpMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IntegrationTestCase extends TestCase
{
    protected ApplicationInterface $app;

    protected function setUp(): void
    {
        throw new \LogicException('This method needs to assign the $app property');
    }

    protected function withoutExceptionHandling(): void
    {
        $this->app->instance(
            ExceptionHandlerInterface::class,
            new class () implements ExceptionHandlerInterface {
                public function handleException(\Throwable $e): Response
                {
                    throw $e;
                }
            },
        );
    }

    protected function call(HttpMethod $method, string $path, string $content = '')
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->get(HttpKernel::class);

        return new TestResponse($kernel->handle($this->prepareRequest($method, $path, $content)));
    }

    private function prepareRequest(HttpMethod $method, string $path, string $content = ''): Request
    {
        return Request::create(
            uri: $path,
            method: $method->value,
            content: $content,
        );
    }
}
