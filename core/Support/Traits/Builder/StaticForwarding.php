<?php

namespace Core\Support\Traits\Builder;

use Core\Database\Doctrine;

trait StaticForwarding
{
    public static function __callStatic($method, $parameters)
    {
        $instance = new static();
        $builder = new Doctrine($instance->table, $instance->hide_fields);

        if (method_exists($builder, $method)) {
            return $builder->$method(...$parameters);
        }

        throw new \Exception("Method {$method} does not exist on Doctrine builder.");
    }
} 