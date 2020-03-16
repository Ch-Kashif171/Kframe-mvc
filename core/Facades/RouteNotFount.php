<?php

namespace Core\Facades;

use Core\Exception\Handlers\RouteNotFoundException;

class RouteNotFount
{

    public static function check() {

        if (env("APP_ENV") != "production") {
            throw new RouteNotFoundException("Your given route did not match");
        } else {
            abort(404);
        }
    }

}