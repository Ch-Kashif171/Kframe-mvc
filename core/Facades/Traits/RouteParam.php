<?php

namespace Core\Facades\Traits;

use stdClass;


trait RouteParam
{

    private static function routeWithValues($route, $action) {

        $routes = new stdClass();
        if (strpos($route,'{') !== false && strpos($route,'}') !== false) {

            $param = static::mapRoute($route, $action);

            if ($param) {
                $routes->route = $action;
                $routes->param = $param;
            }

        } else {
            $routes->route = $action;
            $routes->param = false;
        }

        return $routes;
    }

    private static function mapRoute($route, $action) {


        $get_action = explode('/',$action);
        $get_params = explode('/',$route);


        foreach ($get_params as $key => $get_param) {
            if (strpos($get_param,'{') !== false && strpos($get_param,'}') !== false) {

                return $get_action[$key] ?? false;
                break;
            }
        }

        return false;

    }

}