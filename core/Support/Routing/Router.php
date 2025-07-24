<?php

namespace Core\Support\Routing;

use Closure;
use Core\Support\Traits\Csrf\csrfToken;
use Core\Support\Traits\Middleware;
use Core\Support\Traits\RouteParam;
use Core\Support\Traits\RouteRegistrar;
use Core\Support\Traits\RouteContext;

class Router
{
    use csrfToken, Middleware, RouteParam, RouteRegistrar;

    public static $prefix;
    public static $namespace;
    public static $middleware;
    public static $param = null;
    public static array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    public static array $routeMiddleware = [];
    private static array $routeHandlers = [];
    private static array $dynamicRoutes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * @param $action
     * @param $controllerMethod
     * @return RouteBuilder
     */
    public static function get($action, $controllerMethod): RouteBuilder
    {
        if ($controllerMethod instanceof Closure) {
            $controllerMethod = ['closure' => $controllerMethod];
        }

        $context = new RouteContext(
            'GET',
            $action,
            $controllerMethod,
            static::$prefix,
            static::$namespace,
            static::$middleware
        );

        return static::registerRoute(
            $context,
            self::$routes,
            self::$dynamicRoutes,
            self::$routeHandlers
        );
    }

    /**
     * @param $action
     * @param $controllerMethod
     * @return RouteBuilder
     */
    public static function post($action, $controllerMethod): RouteBuilder
    {
        if ($controllerMethod instanceof Closure) {
            $controllerMethod = ['closure' => $controllerMethod];
        }

        $context = new RouteContext(
            'POST',
            $action,
            $controllerMethod,
            static::$prefix,
            static::$namespace,
            static::$middleware
        );

        return static::registerRoute(
            $context,
            self::$routes,
            self::$dynamicRoutes,
            self::$routeHandlers
        );
    }

    /**
     * @return bool
     * @throws \Core\Exception\Handlers\RouteNotFoundException
     */
    public static function executeRoutes(): bool
    {
        return RouteExecutor::execute(
            $_SERVER['REQUEST_METHOD'],
            RouteAction::current(),
            self::$routeHandlers,
            self::$dynamicRoutes,
            self::$routeMiddleware
        );
    }

    /**
     * @param $options
     * @param Closure $callback
     * @return mixed
     */
    public static function group($options, Closure $callback): mixed
    {
        return RouteGroup::apply($options, $callback);
    }

    /**
     * @param array|null $disable
     * @return void
     */
    public static function authenticate(array $disable = null)
    {
        RouteAuth::load($disable);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function checkMethodNotAllowed()
    {
        MethodChecker::check(self::$routes);
    }
}
