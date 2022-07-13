<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Unit\Core\Filesystem;

use Bambamboole\Framework\Core\Filesystem\FinderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class FinderFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $finder = (new FinderFactory())->create();
        self::assertInstanceOf(Finder::class, $finder);
    }
}