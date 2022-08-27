<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Unit;

trait HelpsWithClosedClasses
{
    protected function getNonPublicProperty(mixed $instance, string $propertyName): mixed
    {
        $reflection = new \ReflectionClass($instance);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
