<?php

namespace Core\Support;


class IsRoute
{

    private static bool $isRoute = false;

    public static function checkRoute($route) {
        static::$isRoute = $route;
    }

    public static function verifyRoute(): bool
    {

        return static::$isRoute;
    }

}