<?php

namespace Core\Support;

use App\Providers\RegisterRoutes;

class RegisterAllRoutes
{
    public static function loadAll()
    {
        $routeFiles = RegisterRoutes::register();
        foreach ($routeFiles as $file) {
            require_once root_path . '/' . $file;
        }
    }
} 