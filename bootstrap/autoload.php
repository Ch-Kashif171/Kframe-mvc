<?php
session_start();

define('root_path', getcwd());

require_once root_path.'/vendor/autoload.php';
require_once root_path.'/core/Dotenv/Dotenv.php';
require_once root_path. '/core/Utils/LoadEnv.php';
require_once root_path.'/core/Utils/helpers.php';
require_once root_path.'/config/app.php';
require_once root_path.'/core/Exception/whoopsExceptionRegister.php';
require_once root_path.'/core/Utils/assetsNotFount.php';
require_once root_path.'/config/mail.php';
require_once root_path.'/core/Support/Redirect.php';
require_once root_path.'/core/Support/Route.php';

$__route_matched = require_once root_path.'/core/Utils/Loader.php';
// Only run 404 check if no route was matched
if (!($__route_matched)) {
    require_once root_path.'/core/Utils/checkMethodNotAllowed.php';
    require_once root_path.'/core/Utils/routeExist.php';
}
