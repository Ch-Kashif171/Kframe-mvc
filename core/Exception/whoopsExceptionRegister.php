<?php

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

if (getenv("APP_ENV") != "production") {
    $whoops->register();
} else {
    $whoops->unregister();
}
