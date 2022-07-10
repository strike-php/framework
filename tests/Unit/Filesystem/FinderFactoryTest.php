<?php

namespace Tests\Bambamboole\Framework\Unit\Filesystem;

use Bambamboole\Framework\Filesystem\FinderFactory;
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
