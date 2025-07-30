<?php

namespace Core\Support\Routing;

use Closure;
use Core\Support\Facades\Route;
use Core\Support\Routing\Router;

class RouteGroup
{
    public static function apply(array $config, Closure $callback)
    {
        // Save previous values
        $prevPrefix = Route::$prefix ?? null;
        $prevNamespace = Route::$namespace ?? null;
        $prevMiddleware = Route::$middleware ?? null;

        // Stack middleware if both exist
        if (isset($config['middleware'])) {
            if ($prevMiddleware) {
                // Merge as array, avoid duplicates
                $newMiddleware = array_merge((array)$prevMiddleware, (array)$config['middleware']);
                Route::$middleware = array_unique($newMiddleware);
                Router::$middleware = array_unique($newMiddleware);
            } else {
                Route::$middleware = $config['middleware'];
                Router::$middleware = $config['middleware'];
            }
        }

        if (isset($config['prefix'])) {
            Route::$prefix = $config['prefix'];
            Router::$prefix = $config['prefix'];
        }
        if (isset($config['namespace'])) {
            Route::$namespace = $config['namespace'];
            Router::$namespace = $config['namespace'];
        }

        $result = $callback();

        // Restore previous values
        Route::$prefix = $prevPrefix;
        Route::$namespace = $prevNamespace;
        Route::$middleware = $prevMiddleware;
        Router::$prefix = $prevPrefix;
        Router::$namespace = $prevNamespace;
        Router::$middleware = $prevMiddleware;

        return $result;
    }

}

