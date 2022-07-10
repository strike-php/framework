<?php

namespace Bambamboole\Framework\Environment;

class Environment
{
    public function __construct(private readonly array $environment = [])
    {
    }

    public function get(string $key, mixed $default = ''): mixed
    {
        return $this->cast($this->environment[$key] ?? $default);
    }

    private function cast(mixed $value): mixed
    {
        return match (gettype($value)) {
            'string' => $this->castString($value),
            'integer' => $value,
            default => throw new \Exception('Cast from type not yet implemented :('),
        };
    }

    private function castString(string $value): string|bool|null
    {
        return match ($value) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value,
        };
    }
}
