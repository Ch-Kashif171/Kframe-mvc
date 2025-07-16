<?php

namespace Core\Support;

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use Closure;
use Core\Exception;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Support\Traits\Csrf\csrfToken;
use Core\Support\Traits\Middleware;
use Core\Support\Traits\RouteParam;
use Core\Support\Traits\RouteRegistrar;
use Core\Support\Traits\RouteContext;

class Route {

    use csrfToken, Middleware, RouteParam, RouteRegistrar;

    public $exit = '';
    public static $prefix;
    public static $namespace;
    public static $middleware;
    public static $param = null;
    public static $routes = [
        'GET' => [],
        'POST' => [],
    ];
    public static $routeMiddleware = []; // Store middleware for individual routes
    private static $routeHandlers = []; // Store route handlers for execution
    // Store dynamic route patterns and their handlers
    private static $dynamicRoutes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * @return string
     */
    public static function action(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        // Remove base path from URI (like /project/public)
        $uri = '/' . ltrim(str_replace($basePath, '', $requestUri), '/');

        // Normalize empty to root
        return $uri === '' ? '/' : $uri;
    }

    /**
     * @param $controller
     * @param $method
     * @param array $params
     * @return mixed
     */
    public static function call($controller, $method, array $params = []) {
        $cont = new $controller();
        $refMethod = new \ReflectionMethod($cont, $method);
        $parameters = $refMethod->getParameters();
        $args = [];
        if (count($parameters) > 0) {
            $firstParam = $parameters[0];
            $type = $firstParam->getType();
            $isRequestType = false;
            if ($type && !$type->isBuiltin()) {
                $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : '';
                if ($typeName === 'Core\\Support\\Request') {
                    $isRequestType = true;
                }
            }
            // If the first parameter is type-hinted as Request or named $request, inject it
            if ($isRequestType || $firstParam->getName() === 'request') {
                $args[] = new Request();
            }
        }
        // Add route params (skip request if already added)
        $args = array_merge($args, $params);
        return $cont->$method(...$args);
    }

    /**
     * @param $action
     * @param $controllerMethod
     * @return RouteBuilder
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function get($action, $controllerMethod) {
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
     * @throws Exception\Handlers\CsrfException
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function post($action, $controllerMethod) {
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
     * Execute the matching route with middleware
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function executeRoutes(): bool
    {
        $currentAction = self::action();
        $method = $_SERVER['REQUEST_METHOD'];
        $routeKey = $method . ':' . $currentAction;
        // 1. Try exact match first
        if (isset(self::$routeHandlers[$routeKey])) {
            $handler = self::$routeHandlers[$routeKey];

            // Apply group middleware first
            $middlewareResult = true;
            if ($handler['middleware']) {
                $middlewareResult = static::applyMiddleware($handler['middleware']);
                if ($middlewareResult !== true) {
                    return true; // Halt and mark as handled if middleware returns anything but true
                }
            }
            
            // Apply route-specific middleware
            if (isset(self::$routeMiddleware[$routeKey])) {
                $middlewareResult = static::applyMiddleware(self::$routeMiddleware[$routeKey]);
                if ($middlewareResult !== true) {
                    return true; // Halt and mark as handled if middleware returns anything but true
                }
            }

            // Enforce CSRF protection for POST requests
            if ($method === 'POST') {
                self::check(); // Provided by csrfToken trait
            }
            
            // Execute the controller
            $routeArgs = $handler['namespace'] ? $handler['namespace'] . '\\' . $handler['controller'] : $handler['controller'];
            if (isset($routeArgs[1])) {
                $controller = $routeArgs[0];
                $method = $routeArgs[1];
            } else {
                throw new RouteNotFoundException("please specify a method in route");
            }
            self::call($controller, $method, []);
            IsRoute::checkRoute(true);
            return true; // Route was matched and executed
        }
        // 2. Try dynamic routes
        foreach (self::$dynamicRoutes[$method] as $route) {
            if (preg_match($route['regex'], $currentAction, $matches)) {
                array_shift($matches); // Remove full match
                $handler = $route;
                // Apply group middleware first
                $middlewareResult = true;
                if ($handler['middleware']) {
                    $middlewareResult = static::applyMiddleware($handler['middleware']);
                    if ($middlewareResult !== true) {
                        return true;
                    }
                }
                // Apply route-specific middleware (not implemented for dynamic routes yet)
                // Enforce CSRF protection for POST requests
                if ($method === 'POST') {
                    self::check();
                }
                $routeArgs = $handler['namespace'] ? $handler['namespace'] . '\\' . $handler['controller'] : $handler['controller'];
                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }
                self::call($controller, $method, $matches);
                IsRoute::checkRoute(true);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $middleware
     * @return bool
     * @throws Exception\Handlers\MiddlewareNotFoundException
     */
    protected static function applyMiddleware($middleware) {
        return static::getMiddleware($middleware);
    }

    /**
     * @param $type
     * @param Closure $routes
     * @return mixed
     */
    public static function group($type, Closure $routes) {

        if (is_array($type) && isset($type['prefix'])) {
            static::$prefix = $type['prefix'];
        }

        if (is_array($type) && isset($type['namespace'])) {
            static::$namespace = $type['namespace'];
        }

        if (is_array($type) && isset($type['middleware'])) {
            static::$middleware = $type['middleware'];
        }

        return $routes();
    }

    /**
     * @throws Exception\Handlers\MiddlewareNotFoundException
     */
    private static function middleware() {

        static::getMiddleware(static::$middleware);
        static::$middleware = null;
    }

    /**
     * @param array|null $disable
     * @return void
     * @throws RouteNotFoundException
     */
    public static function authenticate(array $disable = null)
    {

        Route::get('login', [LoginController::class, 'index']);
        Route::post('login', [LoginController::class, 'login']);
        Route::get('logout', [LoginController::class, 'logout']);

        if (is_array($disable)
            && array_key_exists('register',$disable) && !$disable['register']) {
            /*do nothing*/
        } else {
            Route::get('register', [RegisterController::class, 'register']);
            Route::post('register', [RegisterController::class, 'save']);
        }
    }

    public static function checkMethodNotAllowed() {
        $requestedUri = self::action();
        $method = $_SERVER['REQUEST_METHOD'];
        $otherMethod = $method === 'GET' ? 'POST' : 'GET';
        if (
            !in_array($requestedUri, self::$routes[$method]) &&
            in_array($requestedUri, self::$routes[$otherMethod])
        ) {
            http_response_code(405);
            if (function_exists('config') && config('app.app_env') === 'production') {
                abort(405);
            } else {
                // Use Whoops for pretty error in development
                throw new \Exception('405 Method Not Allowed: This route only supports ' . $otherMethod . ' requests.');
            }
            exit;
        }
    }

}