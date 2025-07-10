<?php

namespace App\Providers;

class RouteServiceProvider
{
    public const BACK_URL = '/';

    public const HOME = '/';

    public static function register(): array
    {
        return [
            'routes/web.php',
            // Add more route files here...
        ];
    }
} 