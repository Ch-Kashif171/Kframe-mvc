<?php
session_start();

define('root_path', getcwd());

require_once root_path.'/vendor/autoload.php';
require_once root_path.'/core/Dotenv/Dotenv.php';
require_once root_path.'/core/LoadEnv.php';
require_once root_path.'/core/helpers.php';
require_once root_path.'/config/app.php';
require_once root_path.'/core/Exception/whoopsExceptionRegister.php';
require_once root_path.'/core/loadfiles.php';
require_once root_path.'/config/mail.php';
require_once root_path.'/core/Redirect.php';
require_once root_path.'/core/route.php';
require_once root_path.'/routes/route.php';
require_once root_path.'/core/routeExist.php';
