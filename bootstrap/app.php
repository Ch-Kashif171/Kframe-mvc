<?php
session_start();

use Core\Foundation\Application;
use Core\Dotenv\Dotenv;
use Core\Support\Redirect;

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

// Only bind class-based services you want to access later
$app->bind('dotenv', new Dotenv(root_path));
$app->bind('redirect', new Redirect());

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
