<?php

use Core\Support\IsRoute;
use Core\Support\Routing\RouteNotFount;

$route = IsRoute::verifyRoute();

$uri = $_SERVER['REQUEST_URI'] ?? '';
if (
    preg_match('#\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$#i', $uri)
) {
    // Skip not found logic for asset requests
    return;
}

if (isset($route) && $route) {
    // Okay, do nothing
} else {
    RouteNotFount::check();
}