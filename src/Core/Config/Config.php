<?php

declare(strict_types=1);

namespace Strike\Framework\Core\Config;

class Config implements ConfigInterface
{
    public function __construct(private array $config = [])
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (\str_contains($key, '.')) {
            $block = $this->config;
            foreach (\explode('.', $key) as $segment) {
                if (\is_array($block) && \array_key_exists($segment, $block)) {
                    $block = $block[$segment];
                } else {
                    return $this->getValue($default);
                }
            }

            return $block;
        }

        return $this->config[$key] ?? $this->getValue($default);
    }

    public function set(string $key, mixed $value): void
    {
        if (\str_contains($key, '.')) {
            $reference = &$this->config;
            $keys = \explode('.', $key);
            foreach ($keys as $i => $segment) {
                if (\count($keys) === 1) {
                    break;
                }
                unset($keys[$i]);
                if (!\array_key_exists($segment, $reference)) {
                    $reference[$segment] = [];
                }
                $reference = &$reference[$segment];
            }
            $reference[\array_shift($keys)] = $value;

            return;
        }

        $this->config[$key] = $value;
    }

    public function all(): array
    {
        return $this->config;
    }

    private function getValue(mixed $value): mixed
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
