<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Aggregators
{
    public static function increment($column, $value = 1)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->increment($column, $value);
    }

    public static function decrement($column, $value = 1)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->decrement($column, $value);
    }

    public static function sum($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->sum($column);
    }

    public static function max($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->max($column);
    }
    
    public static function min($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->min($column);
    }

    public static function count($column = "*")
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->count($column);
    }

    public static function exists()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->exists();
    }
}