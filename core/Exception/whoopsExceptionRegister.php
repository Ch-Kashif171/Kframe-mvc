<?php

$whoops = new \Whoops\Run;
$handler = new \Whoops\Handler\PrettyPageHandler;
$handler->setEditor('vscode');
$handler->setApplicationRootPath(getcwd());
$handler->setPageTitle("Kframe Exception - Something went wrong!");
$handler->addDataTable('Environment', $_ENV);
$handler->addDataTable('Server', $_SERVER);
$handler->addDataTable('Request', $_REQUEST);
$handler->addDataTable('Session', isset($_SESSION) ? $_SESSION : []);
$handler->addDataTable('Cookies', $_COOKIE);
$logFile = getcwd() . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = @file($logFile);
    $recent = $lines ? array_slice($lines, -20) : [];
    $handler->addDataTable('Recent Log Entries', $recent);
}
$whoops->pushHandler($handler);

if (getenv("APP_ENV") != "production") {
    $whoops->register();
} else {
    $whoops->unregister();
}
