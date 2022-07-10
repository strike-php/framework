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
        $app = new Application($basePath, new Config());

        self::assertEquals($basePath, $app->getBasePath());
        self::assertEquals($basePath . '/foo', $app->getBasePath('foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('//foo'));
        self::assertEquals($basePath . '/foo', $app->getBasePath('\\foo'));
    }
}
