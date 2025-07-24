<?php

namespace Core\Support\Routing;

class RouteAction
{
    public static function current(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $uri = '/' . ltrim(str_replace($basePath, '', $requestUri), '/');
        return $uri === '' ? '/' : $uri;
    }

}
