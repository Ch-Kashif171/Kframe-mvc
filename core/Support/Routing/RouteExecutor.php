<?php

namespace Core\Support\Routing;

use Core\Exception\Handlers\CsrfException;
use Core\Exception\Handlers\MiddlewareException;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Support\IsRoute;
use Core\Support\Traits\Csrf\CsrfToken;
use Core\Support\Traits\Middleware;

class RouteExecutor
{
    use CsrfToken, Middleware;

    /**
     * @param $incomingMethod
     * @param $currentAction
     * @param $routeHandlers
     * @param $dynamicRoutes
     * @param $routeMiddleware
     * @return bool
     * @throws \Exception
     */
    public static function execute($incomingMethod, $currentAction, $routeHandlers, $dynamicRoutes, $routeMiddleware): bool
    {
        $routeKey = $incomingMethod . ':' . $currentAction;

        if (isset($routeHandlers[$routeKey])) {
            return static::handleStaticRoute($routeHandlers[$routeKey], $routeKey, $incomingMethod, $routeMiddleware);
        }

        // To check if route method is not exists (wrong method)
        MethodChecker::check($incomingMethod, $currentAction, $routeHandlers);

        return static::handleDynamicRoutes($incomingMethod, $currentAction, $dynamicRoutes[$incomingMethod] ?? []);
    }

    /**
     * @param array $handler
     * @param string $routeKey
     * @param string $incomingMethod
     * @param array $routeMiddleware
     * @return bool
     * @throws CsrfException
     * @throws RouteNotFoundException
     * @throws MiddlewareException|\ReflectionException
     */
    private static function handleStaticRoute(array $handler, string $routeKey, string $incomingMethod, array $routeMiddleware): bool
    {
        if (!static::runMiddlewareChecks($handler['middleware'] ?? [], $routeMiddleware[$routeKey] ?? [])) {
            return true;
        }

        if ($incomingMethod === 'POST') {
            static::checkCsrf();
        }

        if (isset($handler['controller']['closure'])) {
            echo $handler['controller']['closure']();
        } else {
            static::invokeController($handler['namespace'], $handler['controller']);
        }

        IsRoute::checkRoute(true);
        return true;
    }

    /**
     * @param string $incomingMethod
     * @param string $currentAction
     * @param array $routes
     * @return bool
     * @throws CsrfException
     * @throws RouteNotFoundException
     * @throws MiddlewareException|\ReflectionException
     */
    private static function handleDynamicRoutes(string $incomingMethod, string $currentAction, array $routes): bool
    {
        foreach ($routes as $route) {
            if (preg_match($route['regex'], $currentAction, $matches)) {
                array_shift($matches);

                if (!static::runMiddlewareChecks($route['middleware'] ?? [])) {
                    return true;
                }

                if ($incomingMethod === 'POST') {
                    static::checkCsrf();
                }

                static::invokeController($route['namespace'], $route['controller'], $matches);
                IsRoute::checkRoute(true);
                return true;
            }
        }

        return false;
    }

    /**
     * @param array|string $handlerMiddleware
     * @param array $routeMiddleware
     * @return bool
     * @throws MiddlewareException
     */
    private static function runMiddlewareChecks(array|string $handlerMiddleware = [], array $routeMiddleware = []): bool
    {

        return static::getMiddleware($handlerMiddleware) === true
            && static::getMiddleware($routeMiddleware) === true;
    }

    /**
     * @param string|null $namespace
     * @param array $controller
     * @param array $params
     * @return void
     * @throws RouteNotFoundException
     * @throws \ReflectionException
     */
    private static function invokeController(?string $namespace, array $controller, array $params = []): void
    {
        [$controllerClass, $methodName] = $controller;
        if (!$methodName) {
            throw new RouteNotFoundException("Please specify a method.");
        }

        $fqcn = $namespace ? $namespace . '\\' . $controllerClass : $controllerClass;

        RouteCaller::call($fqcn, $methodName, $params);
    }

}

