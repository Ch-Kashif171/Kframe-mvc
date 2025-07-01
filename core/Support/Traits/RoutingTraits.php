<?php

namespace Core\Support\Traits;

use stdClass;
use App\Kernel;
use Core\Exception\Handlers\MiddlewareNotFoundException;

trait RouteParam
{
    /**
     * Match a route pattern (e.g. /product/{id}) to a URI (e.g. /product/1) and extract parameters.
     * Returns an object with 'route' (matched route) and 'params' (array of values).
     */
    private static function routeWithValues($route, $uri)
    {
        $routes = new stdClass();
        $pattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            $routes->route = $route;
            $routes->params = $matches;
        } else {
            $routes->route = $uri;
            $routes->params = [];
        }
        return $routes;
    }
    // ... rest of RouteParam trait ...
    private static function mapRoute($route, $action) {
        $paramRoute = static::reMap($route, $action);
        if ($paramRoute->isMatch && $paramRoute->param) {
            return $paramRoute->param;
        }
        return false;
    }
    private static function reMap($route, $action) {
        $paramRoute = new stdClass();
        $isMatch = false;
        $param = false;
        if (strpos($route,'{') !== false && strpos($route,'}') !== false) {
            $get_action = explode('/',$action);
            $get_params = explode('/',$route);
            if (count($get_action) === count($get_params)) {
                $match = [];
                for ($i =0; $i<count($get_action); $i++) {
                    if ($get_action[$i] == $get_params[$i]) {
                        $match[] = true;
                    } elseif (strpos($get_params[$i], '}') !== false && strpos($get_params[$i],'}') !== false) {
                        $match[] = true;
                        $param = $get_action[$i];
                    } else {
                        $match[] = false;
                    }
                }
                if (in_array(false ,$match)) {
                    $isMatch = false;
                } else {
                    $isMatch = true;
                }
            } else {
                $isMatch = false;
            }
        } else {
            $isMatch = false;
        }
        $paramRoute->isMatch = $isMatch;
        $paramRoute->param = $param;
        return $paramRoute;
    }
}

trait Middleware
{
    public static function getMiddleware ($middlewares) {
        $kernel = new Kernel();
        if (is_array($middlewares)) {
            foreach ($middlewares as $middleware) {
                if (isset($kernel->routeMiddleware[$middleware])) {
                    $middleware_class = new $kernel->routeMiddleware[$middleware]();
                    $middleware_class->handle();
                } else {
                    throw new MiddlewareNotFoundException("Your given middleware did not match");
                }
            }
        } else {
            if (! is_null($middlewares)) {
                if (isset($kernel->routeMiddleware[$middlewares])) {
                    $middleware_class = new $kernel->routeMiddleware[$middlewares]();
                    $middleware_class->handle();
                } else {
                    throw new MiddlewareNotFoundException("Your given middleware did not match");
                }
            }
        }
    }
    public function middleware($middleware) {
        static::getMiddleware($middleware);
    }
} 