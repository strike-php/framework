<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Strike\Framework\Cli\StrikeCli;

class StrikeCliTest extends TestCase
{
    public function testItHasANameAndVersion(): void
    {
        $cli = new StrikeCli();

        self::assertEquals('Strike', $cli->getName());
        self::assertEquals('0.1.0', $cli->getVersion());
    }
}
