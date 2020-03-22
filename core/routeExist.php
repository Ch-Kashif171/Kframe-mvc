<?php

use Core\Facades\RouteNotFount;
use Core\Facades\Traits\IsRoute;

$route = IsRoute::verifyRoute();

if (isset($route) && $route) {
    /*Okay do nothing*/
} else {
    RouteNotFount::check();
}

IsRoute::checkRoute(false);