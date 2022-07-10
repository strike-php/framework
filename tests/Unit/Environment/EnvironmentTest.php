<?php

namespace Tests\Bambamboole\Framework\Unit\Environment;

use Bambamboole\Framework\Environment\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testItSearchesForTheKeyInThePassedArray(): void
    {
        $key = 'FOO';
        $value = 'bar';
        $env = new Environment([$key => $value]);

        self::assertEquals($value, $env->get($key));
    }

    public function testItReturnsTheDefaultIfKeyDoesNotExist(): void
    {
        $env = new Environment();

        self::assertEquals('default', $env->get('FOO', 'default'));
    }

    /** @dataProvider castingTestDataProvider */
    public function testItCastsTheValueCorrectly(array $environment, string $key, mixed $expectedValue): void
    {
        $env = new Environment($environment);

        $value = $env->get($key);

        self::assertEquals($expectedValue, $value);
    }

    private function castingTestDataProvider(): array
    {
        return [
            [['FOO' => 'true'], 'FOO', true],
            [['FOO' => 'false'], 'FOO', false],
            [['FOO' => 'null'], 'FOO', null],
            [['FOO' => 1337], 'FOO', 1337],
        ];
    }
}
