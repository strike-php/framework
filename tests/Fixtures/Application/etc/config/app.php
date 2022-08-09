<?php

declare(strict_types=1);
/**@var $env \Strike\Framework\Core\Environment\Environment */

return [
    'test' => $env->get('FOO'),
];
