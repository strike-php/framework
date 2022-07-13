<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http\Routing;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
}
