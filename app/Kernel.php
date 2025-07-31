<?php

namespace App;

use App\Middleware\Authenticate;
use App\Middleware\Guest;

class Kernel
{

    public array $routeMiddleware = [
        'guest' => Guest::class,
        'auth' => Authenticate::class,
    ];
}