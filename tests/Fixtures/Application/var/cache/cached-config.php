<?php

declare(strict_types=1);

return [
  'app' =>
  [
    'test' => 'bar',
    'debug' => true,
    'timezone' => 'UTC',
    'modules' =>
    [
      0 => 'Strike\\Framework\\Cli\\CliModule',
      1 => 'Strike\\Framework\\Http\\Routing\\RoutingModule',
      2 => 'Strike\\Framework\\Log\\LoggingModule',
    ],
  ],
  'nested' =>
  [
    'aws' =>
    [
      'client_id' => '',
    ],
  ],
];
