<?php

namespace Core\Support;


class IsRoute
{

    private static $isRoute;


    public static function checkRoute($route) {

        static::$isRoute = $route;
    }

    public static function verifyRoute() {

        return static::$isRoute;
    }

}