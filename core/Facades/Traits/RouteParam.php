<?php

namespace Core\Facades\Traits;

use stdClass;


trait RouteParam
{

    private static function routeWithValues($route, $action) {

        $routes = new stdClass();
        if (strpos($route,'{') !== false && strpos($route,'}') !== false
        ) {

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
                        echo "no";
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