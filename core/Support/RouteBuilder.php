<?php

namespace Core\Support;

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
        
        if (is_array($middleware)) {
            Route::$routeMiddleware[$routeKey] = $middleware;
        } else {
            Route::$routeMiddleware[$routeKey] = [$middleware];
        }
        
        return $this;
    }
} 