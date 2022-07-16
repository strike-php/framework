<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): Response;
}
