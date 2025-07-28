<?php

namespace Core\Support\Routing;

class MethodChecker
{
    public static function check($routeMethod)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $altMethod = $method === 'GET' ? 'POST' : 'GET';

        if ($routeMethod !== $method) {
            http_response_code(405);
            if (function_exists('config') && config('app.app_env') === 'production') {
                abort(405);
            } else {
                throw new \Exception("405 Method Not Allowed: This route only supports $altMethod requests.");
            }
        }
    }

}

