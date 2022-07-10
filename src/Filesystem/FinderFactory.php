<?php

namespace Bambamboole\Framework\Filesystem;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}
