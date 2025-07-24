<?php

namespace Core\Support\Routing;

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

class RouteAuth
{
    public static function load(array $disable = null)
    {
        Route::get('login', [LoginController::class, 'index']);
        Route::post('login', [LoginController::class, 'login']);
        Route::get('logout', [LoginController::class, 'logout']);

        if (!($disable['register'] ?? false)) {
            Route::get('register', [RegisterController::class, 'register']);
            Route::post('register', [RegisterController::class, 'save']);
        }
    }

}

