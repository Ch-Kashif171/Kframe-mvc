<?php

use Core\Facades\Request;
use Core\Facades\Traits\IsRoute;
use Core\Facades\Traits\Middleware;
use Core\Facades\Traits\RouteParam;
use Core\Facades\Traits\Csrf\csrfToken;
use Core\Exception\Handlers\RouteNotFoundException;

class Route {

    use csrfToken, Middleware, RouteParam, IsRoute;

    public $exit = '';
    public static $prefix;
    public static $namespace;
    public static $middleware;

    /**
     * @return string
     */
    public static function action()
    {
        $uri     =   $_SERVER['PHP_SELF'];
        $arr     =   explode('index.php',$uri);
        $action  =   $arr[1];

        if ($action == ''){
            $action  =   '/';
        } else {
            $action = rtrim($arr[1], '/');
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

        $base = __DIR__ . '/../';
        require_once($base.'app/controllers/' . $controller . '.php' );

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

        $param = null;
        if (isset($param_action->param) && $param_action->param) {
            $action = $param_action->route;
            $param = $param_action->param;
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

                self::call($controller, $method, $param);

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
     * @param $route
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

    private static function middleware() {

        static::getMiddleware(static::$middleware);
        static::$middleware = null;
    }

    public static function authenticate(array $disable = null)
    {

        Route::get('login', 'Auth\LoginController@index');
        Route::post('login', 'Auth\LoginController@login');
        Route::get('logout', 'Auth\LoginController@logout');

        if (!is_null($disable) && is_array($disable)
            && array_key_exists('register',$disable) && !$disable['register']) {
            /*do nothing*/
        } else {
            Route::get('register', 'Auth\RegisterController@register');
            Route::post('register', 'Auth\RegisterController@save');
        }
    }
}