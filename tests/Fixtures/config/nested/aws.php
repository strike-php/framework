<?php

declare(strict_types=1);
/**@var $env \Strike\Framework\Core\Environment\Environment */

return [
    'client_id' => $env->get('AWS_CLIENT_ID'),
];
