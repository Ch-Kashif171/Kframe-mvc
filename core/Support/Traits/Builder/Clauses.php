<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Clauses
{
    public static function where($column, $condition, $value)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->where($column, $condition, $value);
    }

    public static function orWhere($column, $condition, $value)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->orWhere($column, $condition, $value);
    }

    public static function whereIn($column, $value)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->whereIn($column, $value);
    }

    public static function whereNull($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->whereNull($column);
    }

    public static function whereNotNull($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->whereNotNull($column);
    }

    public static function having($column, $condition, $value)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->having($column, $condition, $value);
    }
}