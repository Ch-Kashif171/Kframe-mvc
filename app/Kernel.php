<?php

namespace App;

use App\Middleware\Authenticate;

class Kernel
{

    public $routeMiddleware = [
        'auth' => Authenticate::class,
    ];
}