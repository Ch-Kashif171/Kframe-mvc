<?php

use App\Providers\RouteServiceProvider;
use Core\Exception\Handlers\MiddlewareNotFoundException;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Support\NotFound;
use Core\Support\Route;

// Initiate Routes
Route::init();

NotFound::home_url(url(RouteServiceProvider::BACK_URL));

// Execute routes with middleware and set a flag if matched
$__route_matched = false;
try {
    $__route_matched = Route::executeRoutes();
} catch (MiddlewareNotFoundException|RouteNotFoundException $e) {
    return true;
}
return $__route_matched;
