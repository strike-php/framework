<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Core;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\ModuleBootstrapper;

class ModuleBootstrapperTest extends TestCase
{
    public function testItRegistersModulesFromConfig(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects(self::once())
            ->method('get')
            ->with('app.modules', [])
            ->willReturn(['TestModule']);

        $app = $this->createMock(ApplicationInterface::class);
        $app
            ->expects(self::once())
            ->method('registerModule')
            ->with('TestModule');

        $moduleBootstrapper = new ModuleBootstrapper($config);
        $moduleBootstrapper->bootstrap($app);
    }
}
