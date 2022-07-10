<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Config;

use Bambamboole\Framework\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testResolveASimpleConfigKey(): void
    {
        $config = new Config(['test' => 'foo']);

        $value = $config->get('test');

        self::assertEquals('foo', $value);
    }

    public function testItCanResolveADottedPath(): void
    {
        $config = new Config(['nested' => ['test' => 'foo']]);

        $value = $config->get('nested.test');

        self::assertEquals('foo', $value);
    }

    public function testIfSimpleKeyIsNotFoundItReturnsTheDefault(): void
    {
        $config = new Config();

        $value = $config->get('test', 'bar');

        self::assertEquals('bar', $value);
    }

    public function testIfNestedKeyIsNotFoundItReturnsTheDefault(): void
    {
        $config = new Config();

        $value = $config->get('test.not.there', 'bar');

        self::assertEquals('bar', $value);
    }

    public function testDefaultClosuresAreExecuted(): void
    {
        $config = new Config();

        $value = $config->get('test', fn () => 'bar');

        self::assertEquals('bar', $value);
    }
}
