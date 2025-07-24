<?php

namespace Core\Support\Routing;

use App\Providers\RouteServiceProvider;
use function coreView;

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