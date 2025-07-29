<?php

namespace Core\Support\Routing;

use Core\Exception\Handlers\CsrfException;
use Core\Exception\Handlers\MiddlewareNotFoundException;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Support\IsRoute;
use Core\Support\Traits\Csrf\CsrfToken;
use Core\Support\Traits\Middleware;

class RouteExecutor
{
    use CsrfToken, Middleware;

    /**
     * @param $method
     * @param $currentAction
     * @param $routeHandlers
     * @param $dynamicRoutes
     * @param $routeMiddleware
     * @return bool
     * @throws RouteNotFoundException
     * @throws CsrfException
     * @throws MiddlewareNotFoundException
     */
    public static function execute($method, $currentAction, $routeHandlers, $dynamicRoutes, $routeMiddleware): bool
    {
        $routeKey = $method . ':' . $currentAction;

        if (isset($routeHandlers[$routeKey])) {
            $handler = $routeHandlers[$routeKey];

            if ($handler['middleware'] && static::getMiddleware($handler['middleware']) !== true) {
                return true;
            }

            if (isset($routeMiddleware[$routeKey]) && static::getMiddleware($routeMiddleware[$routeKey]) !== true) {
                return true;
            }

            if ($method === 'POST') {
                static::checkCsrf();
            }

            if (isset($handler['controller']['closure'])) {
                echo $handler['controller']['closure']();
            } else {
                $namespace = $handler['namespace'];
                [$controller, $methodName] = $handler['controller'];
                if (!$methodName) throw new RouteNotFoundException("Please specify a method.");
                $fqcn = $namespace ? $namespace . '\\' . $controller : $controller;
                RouteCaller::call($fqcn, $methodName);
            }

            IsRoute::checkRoute(true);
            return true;
        }

        foreach ($dynamicRoutes[$method] ?? [] as $route) {
            if (preg_match($route['regex'], $currentAction, $matches)) {
                array_shift($matches);

                if ($route['middleware'] && static::getMiddleware($route['middleware']) !== true) {
                    return true;
                }
                if ($method === 'POST') {
                    static::checkCsrf();
                }

                [$controller, $methodName] = $route['controller'];
                if (!$methodName) {
                    throw new RouteNotFoundException("Please specify a method.");
                }
                $fqcn = $route['namespace'] ? $route['namespace'] . '\\' . $controller : $controller;

                RouteCaller::call($fqcn, $methodName, $matches);
                IsRoute::checkRoute(true);
                return true;
            }
        }

        return false;
    }

}

