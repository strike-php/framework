<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit;

use Bambamboole\Framework\Application;
use Bambamboole\Framework\Config\Config;
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

    public function testItSetsTheCorrectTimezone(): void
    {
        date_default_timezone_set('Europe/Berlin');

        new Application('/test');

        self::assertEquals('UTC', date_default_timezone_get());
    }

    public function testDefaultTimezoneCanBeConfiguredVIaConfig(): void
    {
        date_default_timezone_set('UTC');

        new Application('/test', new Config(['app' => ['timezone' => 'Europe/Berlin']]));

        self::assertEquals('Europe/Berlin', date_default_timezone_get());
    }
}
