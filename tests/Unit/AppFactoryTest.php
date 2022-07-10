<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit;

use Bambamboole\Framework\AppFactory;
use Bambamboole\Framework\Config\Config;
use Bambamboole\Framework\Config\ConfigFactory;
use PHPUnit\Framework\TestCase;

class AppFactoryTest extends TestCase
{
    public function testItCanAddMoreDotEnvFiles(): void
    {
        $configFactory = $this->createMock(ConfigFactory::class);
        $configFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::anything(),
                self::anything(),
                [new \SplFileInfo(__DIR__ . '/../Fixtures/environment/.env')],
            )
            ->willReturn(new Config());
        $factory = new AppFactory(configFactory: $configFactory);

        $factory
            ->withEnvFile(__DIR__ . '/../Fixtures/config/config')
            ->create(__DIR__ . '/../Fixtures/environment/.env');
    }
}
