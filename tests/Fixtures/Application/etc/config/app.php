<?php

declare(strict_types=1);
/**@var $env \Strike\Framework\Core\Environment\Environment */

return [
    'test' => $env->get('FOO'),

    'debug' => $env->get('APP_DEBUG', true),
    'timezone' => $env->get('APP_TIMEZONE', 'UTC'),

    'modules' => [
        \Strike\Framework\Cli\CliModule::class,
        \Strike\Framework\Http\Routing\RoutingModule::class,
        \Strike\Framework\Log\LoggingModule::class,
    ],
];
