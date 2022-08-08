<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core;

use Strike\Framework\Core\ApplicationPaths;
use PHPUnit\Framework\TestCase;

class ApplicationPathsTest extends TestCase
{
    public function testGetBasePathWillTrimDirectorySeparators(): void
    {
        $appPaths = new ApplicationPaths('/');

        $testPath = $appPaths->getBasePath('/test');

        self::assertEquals('/test', $testPath);
    }

    public function testGetBasePathWithoutArgumentReturnsTheBasePath(): void
    {
        $appPaths = new ApplicationPaths('/');

        $basePath = $appPaths->getBasePath();

        self::assertEquals('/', $basePath);
    }

    public function testGetConfigPath()
    {
        $appPaths = new ApplicationPaths('/');

        $configPath = $appPaths->getConfigPath();

        self::assertEquals('/etc/config', $configPath);
    }

    public function testGetCachedConfigPath()
    {
        $appPaths = new ApplicationPaths('/');

        $cachedConfigPath = $appPaths->getCachedConfigPath();

        self::assertEquals('/var/cache/cached-config.php', $cachedConfigPath);
    }

    public function testGetCachedRoutesPath()
    {
        $appPaths = new ApplicationPaths('/');

        $cachedRoutesPath = $appPaths->getCachedRoutesPath();

        self::assertEquals('/var/cache/cached-routes.php', $cachedRoutesPath);
    }

    public function testGetRoutesPath()
    {
        $appPaths = new ApplicationPaths('/');

        $routesPath = $appPaths->getRoutesPath();

        self::assertEquals('/etc/routes.php', $routesPath);
    }

    public function testGetDocumentRoot()
    {
        $appPaths = new ApplicationPaths('/');

        $documentRoot = $appPaths->getDocumentRoot();

        self::assertEquals('/public', $documentRoot);
    }
}
