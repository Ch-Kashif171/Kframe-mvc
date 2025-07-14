<?php

namespace Core\Support\Traits;

class RouteContext
{
    public string $httpMethod;
    public string $action;
    public $controllerMethod;
    public ?string $prefix;
    public ?string $namespace;
    public $middleware;

    public function __construct(
        string $httpMethod,
        string $action,
        $controllerMethod,
        ?string $prefix = null,
        ?string $namespace = null,
        $middleware = null
    ) {
        $this->httpMethod = $httpMethod;
        $this->action = $action;
        $this->controllerMethod = $controllerMethod;
        $this->prefix = $prefix;
        $this->namespace = $namespace;
        $this->middleware = $middleware;
    }
} 