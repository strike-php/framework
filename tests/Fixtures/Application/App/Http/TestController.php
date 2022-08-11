<?php

declare(strict_types=1);

namespace Tests\Strike\Framework\Fixtures\Application\App\Http;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function __invoke(): Response
    {
        return new Response('it works!');
    }
}
