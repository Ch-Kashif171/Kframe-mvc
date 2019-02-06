<?php
session_start();

define('root_path', getcwd(), true);

$base = __DIR__ . '/../';
require_once $base.'vendor/autoload.php';
require_once $base.'core/Dotenv/Dotenv.php';
require_once $base.'core/helpers.php';
require_once $base.'config/app.php';
include_once $base.'core/Exception/ErrorsHandler.php';
require_once $base.'core/loadfiles.php';
require_once $base.'config/mail.php';
require_once $base.'core/Redirect.php';
require_once $base.'core/route.php';
require_once $base.'route/route.php';
require_once $base.'core/routeExist.php';
