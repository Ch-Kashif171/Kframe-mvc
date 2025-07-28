<?php

namespace Core\Support\Traits\Builder;

trait StaticForwarding
{
    public static function __callStatic($method, $parameters)
    {
        $instance = new static();
        $builder = new \Core\Database\QueryBuilder($instance->table, $instance->hidden, static::class);

        if (method_exists($builder, $method)) {
            return $builder->$method(...$parameters);
        }

        throw new \Exception("Method {$method} does not exist on Builder.");
    }
} 