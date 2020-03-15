<?php

use Core\Facades\Request;
use Core\Facades\Session;
use Core\Middleware\Authenticated;
use Core\Facades\Traits\Csrf\csrfToken;
use App\Middleware\Authenticate;


class Route{
    use csrfToken;

    public $exit = '';
    public static $prefix;
    public static $namespace;

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

    public static function routeWithValues($action){
        if (strpos($action,'{') !== false){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $action
     * @param $controller
     * @param $method
     * @return mixed
     */
    public static function call($controller,$method){

        $base = __DIR__ . '/../';
        require_once($base.'app/controllers/' . $controller . '.php' );

        $controller_arr = explode('\\',$controller);
        if ( count($controller_arr)>1) {
            $controller = $controller_arr[1];
        }

        $cont   =   new $controller();
        return $cont->$method( new Request() );

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

        /*route with {id} etc work still pending*/
        /*$find = self::routeWithValues($action);
        if ($find){
            $get_action_array = explode('/',$get_action);
            $get_action = $get_action_array[1];

            $action_array = explode('/',$action);
            $action = $action_array[1];
        }*/

        if ($get_action  ==  $action) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $getBoth = explode('@', $controller_class_and_method);
                if (isset($getBoth[1])) {
                    $controller = $getBoth[0];
                    $method = $getBoth[1];
                } else {
                    echo "please specify a method";
                    exit;
                }

                self::call($controller, $method);

            }

            /*this is check in routeExist file*/
            $_SESSION['exist'] = true;
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
                    echo "please specify a method";
                    exit;
                }
                self::call($controller, $method);

                /*this is check in routeExist file*/
                $_SESSION['exist'] = true;

            } else {
                /*this is check in routeExist file*/
                unset($_SESSION['exist']);
            }
        }

    }

    /**
     * @param $type
     * @param $route
     */
    public static function group($type, Closure $route) {

        if (is_array($type) && isset($type['prefix'])) {
            static::$prefix = $type['prefix'];
        }

        if (is_array($type) && isset($type['namespace'])) {
            static::$namespace = $type['namespace'];
        }

        if (is_array($type) && isset($type['middleware']) && $type['middleware'] == 'auth') {

            //Authenticate::handle($route);
        }

        return $route();

        if(!Session::has('middleware')) {
            if (isset($type['middleware']) && $type['middleware'] == 'auth') {
                /* if (!Auth::check()) {*/
                Session::put('middleware', $type['middleware']);
                $auth = new Authenticated();
                $auth->handle($route());
                /*} else {
                    $route();
                }*/
            }
        }else{
            Session::forget('middleware');
        }
    }

    public static function authenticate(){

        Route::get('login','Auth\LoginController@index');
        Route::post('login','Auth\LoginController@login');
        Route::get('logout','Auth\LoginController@logout');
        Route::get('register','Auth\RegisterController@register');
        Route::post('register','Auth\RegisterController@save');
    }
}