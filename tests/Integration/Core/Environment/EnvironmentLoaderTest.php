<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Integration\Core\Environment;

use Strike\Framework\Core\Environment\EnvironmentLoader;
use PHPUnit\Framework\TestCase;
use Tests\Strike\Framework\Fixtures\HasFixtures;

class EnvironmentLoaderTest extends TestCase
{
    use HasFixtures;

    protected function tearDown(): void
    {
        $_SERVER = [];
        $_ENV = [];
    }

    public function testItLoadsValuesFromEnvFiles(): void
    {
        $loader = new EnvironmentLoader();
        $files = [new \SplFileInfo($this->getTestingApplicationBasePath('.env'))];
        $env = $loader->load($files);

        // FOO is a key in the loaded .env file
        self::assertEquals('bar', $env->get('FOO'));
    }

    public function testItCanLoadMultipleFilesFromTheSamePath(): void
    {
        $loader = new EnvironmentLoader();

        $files = [
            new \SplFileInfo($this->getTestingApplicationBasePath('.env')),
            new \SplFileInfo($this->getTestingApplicationBasePath('.env-2')),
        ];
        $env = $loader->load($files);

        self::assertEquals('bar', $env->get('FOO'));
        // BAR is the key in the loaded .env-2 file
        self::assertEquals('baz', $env->get('BAR'));
    }
}
