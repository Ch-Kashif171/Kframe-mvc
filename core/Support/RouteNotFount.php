<?php

namespace Core\Support;

use Core\Exception\Handlers\RouteNotFoundException;

class RouteNotFount
{

    public static function check() {

        if (config("app.app_env") != "production") {
            throw new RouteNotFoundException("Your given route did not match");
        } else {
            abort(404);
        }
    }

}