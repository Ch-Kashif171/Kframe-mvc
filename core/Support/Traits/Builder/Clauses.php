<?php

namespace Core\Support\Traits\Builder;

trait Clauses
{
    public static function where($column, $condition, $value)
    {
        $instance = new static();
        return $instance->doctrine->where($column, $condition, $value);
    }

    public static function orWhere($column, $condition, $value)
    {
        $instance = new static();
        return $instance->doctrine->orWhere($column, $condition, $value);
    }

    public static function whereIn($column, $value)
    {
        $instance = new static();
        return $instance->doctrine->whereIn($column, $value);
    }

    public static function whereNull($column)
    {
        $instance = new static();
        return $instance->doctrine->whereNull($column);
    }

    public static function whereNotNull($column)
    {
        $instance = new static();
        return $instance->doctrine->whereNotNull($column);
    }

    public static function having($column, $condition, $value)
    {
        $instance = new static();
        return $instance->doctrine->having($column, $condition, $value);
    }

}