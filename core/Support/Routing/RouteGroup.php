<?php

namespace Core\Support\Routing;

use Closure;

class RouteGroup
{
    public static function apply(array $config, Closure $callback)
    {
        if (isset($config['prefix'])) Route::$prefix = $config['prefix'];
        if (isset($config['namespace'])) Route::$namespace = $config['namespace'];
        if (isset($config['middleware'])) Route::$middleware = $config['middleware'];
        return $callback();
    }

}

