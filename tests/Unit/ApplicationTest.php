<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit;

use Bambamboole\Framework\Application;
use Bambamboole\Framework\Environment\Environment;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testGetBasePath(): void
    {
        $basePath = '/test';
        $app = new Application($basePath);

        self::assertEquals($basePath, $app->getBasePath());
        self::assertEquals($basePath . '/foo', $app->getBasePath('foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('//foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('\\foo'));
    }

    public function testGetConfigPath(): void
    {
        $basePath = '/test';
        $app = new Application($basePath, new Environment(['APP_CONFIG_PATH' => 'something-else']));

        self::assertEquals('/test/something-else', $app->getConfigPath());
    }
}
