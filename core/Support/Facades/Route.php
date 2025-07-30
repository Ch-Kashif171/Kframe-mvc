<?php

namespace Core\Support\Facades;

use Closure;

/**
 * @method static \Core\Support\Facades\Route get(string $uri, \Closure|array|string|null $action = null)
 * @method static \Core\Support\Facades\Route post(string $uri, \Closure|array|string|null $action = null)
 * @method static \Core\Support\Facades\Route put(string $uri, \Closure|array|string|null $action = null)
 * @method static \Core\Support\Facades\Route delete(string $uri, \Closure|array|string|null $action = null)
 * @method static \Core\Support\Facades\Route patch(string $uri, \Closure|array|string|null $action = null)
 * @method static \Core\Support\Facades\Route group($options, Closure $callback)
 * @method static \Core\Support\Facades\Route authenticate(array $disable = null)
 * @method static \Core\Support\Facades\Route checkMethodNotAllowed()
 * @method static \Core\Support\Facades\Route executeRoutes()
 * @method static \Core\Support\Facades\Route addRouteMiddleware($routeKey, $middleware)
 * @method static \Core\Support\Facades\Route middleware($middleware)
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