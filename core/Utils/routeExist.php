<?php

use Core\Support\RouteNotFount;
use Core\Support\IsRoute;

$route = IsRoute::verifyRoute();

if (isset($route) && $route) {
    /*Okay do nothing*/
} else {
    RouteNotFount::check();
}

IsRoute::checkRoute(false);