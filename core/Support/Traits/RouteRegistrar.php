<?php

namespace Core\Support\Traits;

use Core\Support\Routing\RouteBuilder;

trait RouteRegistrar
{
    /**
     * Register a route for a given HTTP method (GET, POST, etc.)
     *
     * @param RouteContext $context
     * @param array &$routes
     * @param array &$dynamicRoutes
     * @param array &$routeHandlers
     * @return object RouteBuilder
     */
    protected static function registerRoute(
        RouteContext $context,
        array &$routes,
        array &$dynamicRoutes,
        array &$routeHandlers
    ) {
        $action = ltrim($context->action, '/');
        $action_route = '/' . $action;
        $action = $context->prefix ? '/' . $context->prefix . $action_route : $action_route;
        // Check for dynamic segments
        if (str_contains($action, '{')) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $action);
            $regex = '#^' . $pattern . '$#';
            $dynamicRoutes[$context->httpMethod][] = [
                'regex' => $regex,
                'route' => $action,
                'controller' => $context->controllerMethod,
                'middleware' => $context->middleware,
                'namespace' => $context->namespace
            ];
        } else {
            $routes[$context->httpMethod][] = $action;
            $routeKey = $context->httpMethod . ':' . $action;
            $routeHandlers[$routeKey] = [
                'controller' => $context->controllerMethod,
                'middleware' => $context->middleware,
                'namespace' => $context->namespace
            ];
        }

        return new RouteBuilder($action, $context->httpMethod, $context->controllerMethod);
    }
} 