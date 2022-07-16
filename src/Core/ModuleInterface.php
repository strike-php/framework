<?php

declare(strict_types=1);

namespace Strike\Framework\Core;

interface ModuleInterface
{
    public function register(): void;

    public function load(): void;
}
