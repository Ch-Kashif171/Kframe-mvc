<?php

namespace Core;

use Closure;
use Core\Facades\Request;
use Core\Facades\IsRoute;
use Core\Facades\Traits\Middleware;
use Core\Facades\Traits\RouteParam;
use Core\Facades\Traits\Csrf\csrfToken;
use Core\Exception\Handlers\RouteNotFoundException;

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
    public static function call($controller,$method, $param = null){

        require_once(base_path().'/app/Controllers/' . $controller . '.php' );

        $controller_arr = explode('\\',$controller);
        if ( count($controller_arr)>1) {
            $controller = $controller_arr[1];
        }

        $cont   =   new $controller();

        if (is_null($param)) {
            return $cont->$method( new Request() );
        } else {
            return $cont->$method($param);
        }

    }

    /**
     * @param $action
     * @param $controller_class_and_method
     */
    public static function get($action,$controller_class_and_method) {

        $get_action =   self::action();

        $action =    ltrim($action,'/');
        $action_route =   '/'.$action;

        $action = static::$prefix ? '/'.static::$prefix.$action_route : $action_route;

        $controller_class_and_method = static::$namespace ? static::$namespace.'\\'.$controller_class_and_method : $controller_class_and_method;

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
                $getBoth = explode('@', $controller_class_and_method);
                if (isset($getBoth[1])) {
                    $controller = $getBoth[0];
                    $method = $getBoth[1];
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
    public static function post($action,$controller_class_and_method){

        $get_action =   self::action();
        $action =    ltrim($action,'/');
        $action_route =   '/'.$action;

        $action = static::$prefix ? '/'.static::$prefix.$action_route : $action_route;

        $controller_class_and_method = static::$namespace ? static::$namespace.'\\'.$controller_class_and_method : $controller_class_and_method;

        if($get_action  ==  $action) {

            static::middleware(); /*block route access if not authenticate*/

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                /*********************************************
                 **here check CSRF TOKEN To validate request**
                /********************************************/
                static::check();

                $getBoth = explode('@', $controller_class_and_method);
                if (isset($getBoth[1])) {
                    $controller = $getBoth[0];
                    $method = $getBoth[1];
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

        Route::get('login', 'Auth\LoginController@index');
        Route::post('login', 'Auth\LoginController@login');
        Route::get('logout', 'Auth\LoginController@logout');

        if (is_array($disable)
            && array_key_exists('register',$disable) && !$disable['register']) {
            /*do nothing*/
        } else {
            Route::get('register', 'Auth\RegisterController@register');
            Route::post('register', 'Auth\RegisterController@save');
        }
    }
}