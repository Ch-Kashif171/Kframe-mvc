<?php

namespace App;

use App\Middleware\Authenticate;

class Kernel
{

    public array $routeMiddleware = [
        'auth' => Authenticate::class,
    ];
}