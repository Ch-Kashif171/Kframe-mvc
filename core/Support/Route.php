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
use function base_path;
use function config;

class Route {

    use csrfToken, Middleware, RouteParam;

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

    /**
     * @return string
     */
    public static function action(): string
    {
        $uri     =   $_SERVER['REQUEST_URI'];
        $arr     =   explode('/',$uri);
        unset($arr[0]);
        unset($arr[1]);
        $action = implode('/', $arr);

        if ($action == ''){
            $action  =   '/';
        } else {
            $action = '/' . $action;
        }

        return $action;
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
        $action = ltrim($action, '/');
        $action_route = '/' . $action;
        $action = static::$prefix ? '/' . static::$prefix . $action_route : $action_route;
        self::$routes['GET'][] = $action;
        
        // Store route information for later execution
        $routeKey = 'GET:' . $action;
        self::$routeHandlers[$routeKey] = [
            'controller' => $controllerMethod,
            'middleware' => static::$middleware,
            'namespace' => static::$namespace
        ];
        
        // Create RouteBuilder for chaining
        $routeBuilder = new RouteBuilder($action, 'GET', $controllerMethod);
        
        return $routeBuilder;
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
        $action = ltrim($action, '/');
        $action_route = '/' . $action;
        $action = static::$prefix ? '/' . static::$prefix . $action_route : $action_route;
        self::$routes['POST'][] = $action;
        
        // Store route information for later execution
        $routeKey = 'POST:' . $action;
        self::$routeHandlers[$routeKey] = [
            'controller' => $controllerMethod,
            'middleware' => static::$middleware,
            'namespace' => static::$namespace
        ];
        
        // Create RouteBuilder for chaining
        $routeBuilder = new RouteBuilder($action, 'POST', $controllerMethod);
        
        return $routeBuilder;
    }

    /**
     * Execute the matching route with middleware
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function executeRoutes() {
        $currentAction = self::action();
        $method = $_SERVER['REQUEST_METHOD'];
        $routeKey = $method . ':' . $currentAction;
        
        // Check if we have a handler for this route
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
        } else {
            return false; // No route matched
        }
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