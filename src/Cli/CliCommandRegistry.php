<?php

declare(strict_types=1);

namespace Strike\Framework\Cli;

use Closure;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class CliCommandRegistry implements CommandLoaderInterface
{
    private array $commands = [];

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function add(string $command, ?Closure $factory = null): void
    {
        if (!\method_exists($command, 'getDefaultName')) {
            throw new \Exception('Command seems not to extend Symfony Command');
        }
        $signature = $command::getDefaultName();
        if (\is_null($factory)) {
            $factory = fn (ContainerInterface $container) => $container->get($command);
        }

        $this->commands[$signature] = $factory;
    }

    public function get(string $name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException("command {$name} not registered");
        }

        return \call_user_func($this->commands[$name], $this->container);
    }

    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    public function getNames(): array
    {
        return \array_keys($this->commands);
    }
}
