<?php

declare(strict_types=1);

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/' => [[['_route' => 'test.routes', '_controller' => 'Tests\\Strike\\Framework\\Fixtures\\Application\\App\\Http\\TestController', '_middleware' => []], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/([^/]++)(*:16)'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        16 => [
            [['_route' => 'test.routes.post', '_controller' => 'Tests\\Strike\\Framework\\Fixtures\\Application\\App\\Http\\TestController', '_middleware' => []], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
