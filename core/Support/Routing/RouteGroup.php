<?php

namespace Core\Support\Routing;

use Closure;
use Core\Support\Facades\Route;

class RouteGroup
{
    /**
     * Apply group configuration to routes
     */
    public static function apply(array $config, Closure $callback)
    {
        $previousState = self::savePreviousState();
        self::applyGroupConfig($config);
        
        $result = $callback();
        
        self::restorePreviousState($previousState);
        
        return $result;
    }

    /**
     * Save the current state before applying group config
     * @return array
     */
    private static function savePreviousState(): array
    {
        return [
            'prefix' => Router::$prefix ?? null,
            'namespace' => Router::$namespace ?? null,
            'middleware' => Router::$middleware ?? null,
        ];
    }

    /**
     * Apply group configuration (middleware, prefix, namespace)
     */
    private static function applyGroupConfig(array $config): void
    {
        if (isset($config['middleware'])) {
            self::applyMiddleware($config['middleware']);
        }

        if (isset($config['prefix'])) {
            Router::$prefix = $config['prefix'];
        }

        if (isset($config['namespace'])) {
            Router::$namespace = $config['namespace'];
        }
    }

    /**
     * Apply middleware with stacking support
     */
    private static function applyMiddleware($newMiddleware): void
    {
        $currentMiddleware = Router::$middleware ?? null;
        
        if ($currentMiddleware) {
            // Merge as array, avoid duplicates
            $mergedMiddleware = array_merge((array)$currentMiddleware, (array)$newMiddleware);
            Router::$middleware = array_unique($mergedMiddleware);
        } else {
            Router::$middleware = $newMiddleware;
        }
    }

    /**
     * Restore the previous state after group execution
     */
    private static function restorePreviousState(array $previousState): void
    {
        Router::$prefix = $previousState['prefix'];
        Router::$namespace = $previousState['namespace'];
        Router::$middleware = $previousState['middleware'];
    }
}

