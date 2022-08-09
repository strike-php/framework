<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

enum HttpMethod: string
{
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
}
