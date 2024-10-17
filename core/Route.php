<?php

namespace Core;

use Closure;
use Core\Facades\Request;
use Core\Facades\IsRoute;
use Core\Facades\Traits\Middleware;
use Core\Facades\Traits\RouteParam;
use Core\Facades\Traits\Csrf\csrfToken;
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
     * @param $action
     * @param $controller
     * @param $method
     * @return mixed
     */
    public static function call($controller, $method, $param = null) {

        $cont = new $controller();

        if (is_null($param)) {
            return $cont->$method( new Request() );
        } else {
            return $cont->$method($param);
        }

    }

    /**
     * @param $action
     * @param $controllerMethod
     * @return void
     * @throws Exception\Handlers\MiddlewareNotFoundException
     * @throws RouteNotFoundException
     */
    public static function get($action, $controllerMethod) {

        $get_action =  self::action();

        $action =    ltrim($action,'/');
        $action_route =   '/'.$action;

        $action = static::$prefix ? '/'.static::$prefix.$action_route : $action_route;

        $routeArgs = static::$namespace ? static::$namespace.'\\'.$controllerMethod : $controllerMethod;

        /*********************************************
        ************here get route param**************
        /********************************************/
        $param_action = static::routeWithValues($action, $get_action);

        if (isset($param_action->param) && $param_action->param) {
            $action = $param_action->route;
            static::$param = $param_action->param;
        }

        if ($get_action  ==  $action) {

            static::middleware(); /*block route access if not authenticate*/

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }

                self::call($controller, $method, static::$param);

            }

            /*this is check in routeExist file*/
            IsRoute::checkRoute(true);
        }
    }

    /**
     * @param $action
     * @param $controller_class_and_method
     */
    public static function post($action,$controllerMethod){

        $get_action =   self::action();
        $action =    ltrim($action,'/');
        $action_route =   '/'.$action;

        $action = static::$prefix ? '/'.static::$prefix.$action_route : $action_route;

        $routeArgs = static::$namespace ? static::$namespace.'\\'.$controllerMethod : $controllerMethod;

        if($get_action  ==  $action) {

            static::middleware(); /*block route access if not authenticate*/

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                /*********************************************
                 **here check CSRF TOKEN To validate request**
                /********************************************/
                static::check();

                if (isset($routeArgs[1])) {
                    $controller = $routeArgs[0];
                    $method = $routeArgs[1];
                } else {
                    throw new RouteNotFoundException("please specify a method in route");
                }
                self::call($controller, $method);

                /*this is check in routeExist file*/
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
}