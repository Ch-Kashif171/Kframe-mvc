<?php

namespace Core\Support\Routing;

use Core\Support\Constants;

class MethodChecker
{
    /**
     * Check if the incoming HTTP method matches the route's defined method
     * @param $incomingMethod
     * @param $currentAction
     * @param $routeHandlers
     * @return void
     * @throws \Exception
     */
    public static function check($incomingMethod, $currentAction, $routeHandlers): void
    {
        $allowedMethods = [];
        foreach (Constants::METHODS as $httpMethod) {
            $checkRouteKey = $httpMethod . ':' . $currentAction;
            if (isset($routeHandlers[$checkRouteKey])) {
                $allowedMethods[] = $httpMethod;
            }
        }

        // If route exists but with different method, throw 405
        if (!empty($allowedMethods) && !in_array(strtoupper($incomingMethod), array_map('strtoupper', $allowedMethods))) {
            http_response_code(405);
            if (function_exists('config') && config('app.app_env') === 'production') {
                abort(405);
            } else {
                $methods = implode(', ', $allowedMethods);
                throw new \Exception("405 Method Not Allowed: This route only supports {$methods} requests, but received {$incomingMethod}.");
            }
        }
    }

}

