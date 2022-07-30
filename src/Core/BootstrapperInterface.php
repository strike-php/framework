<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

interface BootstrapperInterface
{
    public function bootstrap(ApplicationInterface $application): void;
}
