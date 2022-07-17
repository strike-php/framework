<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Http;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\Application;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Exception\ExceptionHandlerInterface;
use Strike\Framework\Http\HttpKernel;
use Strike\Framework\Http\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernelTest extends TestCase
{
    private MockObject|Application $app;
    private MockObject|Router $router;
    private MockObject|ConfigInterface $config;

    protected function setUp(): void
    {
        $this->app = $this->createMock(Application::class);
        $this->router = $this->createMock(Router::class);
        $this->config = $this->createMock(ConfigInterface::class);
    }

    public function testItCallsTheExceptionHandlerIfAnExceptionIsThrown(): void
    {
        $exception = new Exception('test-exception');
        $expectedResponse = new Response();
        $exceptionHandler = $this->createMock(ExceptionHandlerInterface::class);
        $exceptionHandler
            ->expects(self::once())
            ->method('handleException')
            ->with($exception)
            ->willReturn($expectedResponse);
        $this->app
            ->expects(self::once())
            ->method('boot')
            ->willThrowException($exception);
        $this->app
            ->expects(self::once())
            ->method('get')
            ->with(ExceptionHandlerInterface::class)
            ->willReturn($exceptionHandler);

        $kernel = new HttpKernel($this->app, $this->router, $this->config);

        $response = $kernel->handle(new Request());

        self::assertSame($expectedResponse, $response);
    }
}
