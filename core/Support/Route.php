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
     * @return void
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function get($action, $controllerMethod) {
        $get_action = self::action();
        $action = ltrim($action, '/');
        $action_route = '/' . $action;
        $action = static::$prefix ? '/' . static::$prefix . $action_route : $action_route;
        self::$routes['GET'][] = $action;
        $routeArgs = static::$namespace ? static::$namespace . '\\' . $controllerMethod : $controllerMethod;
        $param_action = static::routeWithValues($action, $get_action);
        $params = [];
        $isMatch = false;
        if (!empty($param_action->params)) {
            $isMatch = true;
            $params = $param_action->params;
            static::$param = $params;
        } elseif ($get_action == $action) {
            $isMatch = true;
        }
        if ($isMatch) {
            static::middleware();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }
                self::call($controller, $method, $params);
            }
            IsRoute::checkRoute(true);
        }
    }

    /**
     * @param $action
     * @param $controllerMethod
     * @return void
     * @throws Exception\Handlers\CsrfException
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function post($action,$controllerMethod){
        $get_action =   self::action();
        $action =    ltrim($action,'/');
        $action_route =   '/'.$action;
        $action = static::$prefix ? '/'.static::$prefix.$action_route : $action_route;
        self::$routes['POST'][] = $action;
        $routeArgs = static::$namespace ? static::$namespace.'\\'.$controllerMethod : $controllerMethod;
        if($get_action  ==  $action) {
            static::middleware();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                static::check();
                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }
                $requestWasSuccessful = false;
                try {
                    self::call($controller, $method);
                    $requestWasSuccessful = true;
                } catch (\Exception $e) {
                    // Do not rotate token on error
                    throw $e;
                }
                if ($requestWasSuccessful) {
                    self::rotateToken();
                }
                IsRoute::checkRoute(true);
            }
        }
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
                include base_path('views/errors/405.php');
            } else {
                // Use Whoops for pretty error in development
                throw new \Exception('405 Method Not Allowed: This route only supports ' . $otherMethod . ' requests.');
            }
            exit;
        }
    }
}