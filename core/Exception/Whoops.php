<?php

namespace Core\Exception;

use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops
{
    public static function handle()
    {
        $whoops = new Run;
        $handler = new PrettyPageHandler;
        $handler->setEditor('vscode');
        $handler->setApplicationRootPath(root_path);
        $handler->setPageTitle("Kframe Exception - Something went wrong!");
        $handler->addDataTable('Environment', $_ENV);
        $handler->addDataTable('Server', $_SERVER);
        $handler->addDataTable('Request', $_REQUEST);
        $handler->addDataTable('Session', isset($_SESSION) ? $_SESSION : []);
        $handler->addDataTable('Cookies', $_COOKIE);

        $logFile = root_path . '/logs/kframe.log';
        if (file_exists($logFile)) {
            $lines = @file($logFile);
            $recent = $lines ? array_slice($lines, -20) : [];
            $handler->addDataTable('Recent Log Entries', $recent);
        }

        if (config("app.app_env") != "production") {
            $whoops->pushHandler($handler);
            // File logger LAST
            $whoops->pushHandler(new CallbackHandler(function ($exception, $inspector, $run) {
                Log::error($exception, 'WHOOPS');
            }));

            $whoops->register();
        } else {
            $whoops->pushHandler(function ($exception, $inspector, $run) {
                Log::error($exception, 'WHOOPS_PROD');
                abort(500);
            });

            $whoops->register();
        }

        return true;
    }
}
