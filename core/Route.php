<?php

namespace Core;

use Closure;
use Core\Support\General;
use Core\Support\Request;
use Core\Support\IsRoute;
use Core\Support\Traits\Middleware;
use Core\Support\Traits\RouteParam;
use Core\Support\Traits\Csrf\csrfToken;
use Core\Exception\Handlers\RouteNotFoundException;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

class Route {

    use csrfToken, Middleware, RouteParam;

    public $exit = '';
    public static $prefix;
    public static $namespace;
    public static $middleware;
    public static $param = null;

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
        return $cont->$method(new Request(), ...$params);
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
        $routeArgs = static::$namespace ? static::$namespace . '\\' . $controllerMethod : $controllerMethod;
        $param_action = static::routeWithValues($action, $get_action);
        if (!empty($param_action->params)) {
            $action = $param_action->route;
            static::$param = $param_action->params;
        }
        if ($get_action == $action) {
            static::middleware();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }
                $params = static::$param ?? [];
                self::call($controller, $method, $params);
            } else {
                throw new RouteNotFoundException("Post route is not available.");
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
            } else {
                throw new RouteNotFoundException("Get route is not available.");
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
}