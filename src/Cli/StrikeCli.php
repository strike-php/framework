<?php

declare(strict_types=1);

namespace Strike\Framework\Cli;

use Symfony\Component\Console\Application;

class StrikeCli extends Application
{
    public function __construct(string $name = 'Strike', string $version = '0.1.0')
    {
        parent::__construct($name, $version);
    }
}
