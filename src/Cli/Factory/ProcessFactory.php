<?php

declare(strict_types=1);

namespace Strike\Framework\Cli\Factory;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    public function getInstance(array $arguments, ?string $currentWorkingDirectory = null): Process
    {
        return new Process($arguments, $currentWorkingDirectory);
    }
}
