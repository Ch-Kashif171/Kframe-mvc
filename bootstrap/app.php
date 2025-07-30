<?php
session_start();

if (!defined('root_path')) {
    define('root_path', dirname(__DIR__));
}

require_once __DIR__ . '/../vendor/autoload.php';


use Core\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new kframe application instance
| which serves as the "glue" for all the components of kframe, and is
| binding the system's all the various parts.
|
*/
$app = new Application();

//  Boot the application: load files and register services in order.
$app->boot();

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/
return $app;
