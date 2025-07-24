<?php

namespace Core\Support\Routing;

use Core\Support\Facades\Route;

class RouteBuilder
{
    private $action;
    private $method;
    private $controllerMethod;

    public function __construct($action, $method, $controllerMethod)
    {
        $this->action = $action;
        $this->method = $method;
        $this->controllerMethod = $controllerMethod;
    }

    /**
     * Add middleware to the route
     * @param string|array $middleware
     * @return RouteBuilder
     */
    public function middleware($middleware)
    {
        $routeKey = $this->method . ':' . $this->action;
        Route::addRouteMiddleware($routeKey, is_array($middleware) ? $middleware : [$middleware]);
        return $this;
    }
} 