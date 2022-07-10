<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Integration\Environment;

use Bambamboole\Framework\Environment\EnvironmentLoader;
use PHPUnit\Framework\TestCase;

class EnvironmentLoaderTest extends TestCase
{
    protected function tearDown(): void
    {
        $_SERVER = [];
        $_ENV = [];
    }

    public function testItLoadsValuesFromEnvFiles(): void
    {
        $loader = new EnvironmentLoader();
        $files = [new \SplFileInfo(__DIR__ . '/fixtures/.env')];
        $env = $loader->load($files);

        // FOO is a key in the loaded .env file
        self::assertEquals('bar', $env->get('FOO'));
    }

    public function testItCanLoadMultipleFilesFromTheSamePath(): void
    {
        $loader = new EnvironmentLoader();

        $files = [
            new \SplFileInfo(__DIR__ . '/fixtures/.env'),
            new \SplFileInfo(__DIR__ . '/fixtures/.env-2'),
        ];
        $env = $loader->load($files);

        self::assertEquals('bar', $env->get('FOO'));
        // BAR is the key in the loaded .env-2 file
        self::assertEquals('baz', $env->get('BAR'));
    }
}
