<?php

namespace Core\Support\Routing;

use Core\Support\Request;

class RouteCaller
{
    /**
     * @param $controller
     * @param $method
     * @param array $params
     * @return mixed
     * @throws \ReflectionException
     */
    public static function call($controller, $method, array $params = []): mixed
    {
        $instance = new $controller();
        $refMethod = new \ReflectionMethod($instance, $method);
        $args = [];

        $parameters = $refMethod->getParameters();
        if (!empty($parameters)) {
            $first = $parameters[0];
            $type = $first->getType();
            $isRequestType = $type && !$type->isBuiltin() && (
                    ($type instanceof \ReflectionNamedType && $type->getName() === Request::class) ||
                    $first->getName() === 'request'
                );
            if ($isRequestType) {
                $args[] = new Request();
            }
        }

        $args = array_merge($args, $params);
        return $instance->$method(...$args);
    }

}

