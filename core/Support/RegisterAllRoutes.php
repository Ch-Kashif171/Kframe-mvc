<?php

namespace Core\Support;

use App\Providers\RouteServiceProvider;

class RegisterAllRoutes
{
    public static function loadAll()
    {
        $routeFiles = RouteServiceProvider::register();
        foreach ($routeFiles as $file) {
            require_once root_path . '/' . $file;
        }

        // Default documentation route
        Route::get('/documentation', function () {
            return coreView('core.documentation.index');
        });
    }
} 