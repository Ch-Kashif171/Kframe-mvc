<?php

namespace Core\Support\Facades;

use Closure;

/**
 * @method static \Core\Support\Routing\Router get($action, $controllerMethod)
 * @method static \Core\Support\Routing\Router post($action, $controllerMethod)
 * @method static \Core\Support\Routing\Router group($options, Closure $callback)
 * @method static \Core\Support\Routing\Router authenticate(array $disable = null)
 * @method static \Core\Support\Routing\Router checkMethodNotAllowed()
 * @method static \Core\Support\Routing\Router executeRoutes()
 * @method static \Core\Support\Routing\Router addRouteMiddleware($routeKey, $middleware)
 *
 * @see \Core\Support\Routing\Router
 */
class Route extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'router';
    }
}