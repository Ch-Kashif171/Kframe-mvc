<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Guest;

class Kernel
{

    public array $routeMiddleware = [
        'guest' => Guest::class,
        'auth' => Authenticate::class,
    ];
}