<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Aggregators
{
    public static function increment($column, $value = 1)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->increment($column, $value);
    }

    public static function decrement($column, $value = 1)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->decrement($column, $value);
    }

    public static function sum($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->sum($column);
    }

    public static function max($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->max($column);
    }
    
    public static function min($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->min($column);
    }

    public static function count()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->count();
    }

    public static function exists()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->exists();
    }
}