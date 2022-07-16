<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Filesystem;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}
