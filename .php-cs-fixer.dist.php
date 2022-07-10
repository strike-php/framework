<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,
    'no_unused_imports' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'declare_strict_types' => true,
    'trailing_comma_in_multiline' => [
        'elements' => [
            'arrays',
            'arguments',
            'parameters',
        ],
    ],
    'no_extra_blank_lines' => [
        'tokens' => [
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'switch',
            'throw',
            'use',
        ],
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'return',
        ],
    ],
    'function_typehint_space' => true,
    'native_function_invocation' => [
        'include' => ['@internal'],
        'scope' => 'namespaced',
        'strict' => true,
    ],
];

$finder = Finder::create()
    ->in(__DIR__);

return (new Config())
    ->setRules($rules)
    ->setFinder($finder);
