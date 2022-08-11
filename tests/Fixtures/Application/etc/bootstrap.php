<?php

declare(strict_types=1);

use Strike\Framework\Core\Application;
use Strike\Framework\Core\Config\ConfigBootstrapper;
use Strike\Framework\Core\Config\ConfigInterface;
use Strike\Framework\Core\Exception\ExceptionBootstrapper;
use Strike\Framework\Core\ModuleBootstrapper;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

$app = new Application(
    basePath: dirname(__DIR__),
    bootstrappers: [
        (new ConfigBootstrapper())
            ->disableConfigCache()
            ->configure(function (ConfigInterface $config) {
                $config->set('http.routing.cache', false);
            }),
        ExceptionBootstrapper::class,
        ModuleBootstrapper::class,
    ],
);

return $app;
