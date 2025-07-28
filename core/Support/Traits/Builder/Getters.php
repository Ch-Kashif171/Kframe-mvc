<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Getters
{
    public static function all()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->get();
    }

    public static function get()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->get();
    }

    public static function pluck(...$columns)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->pluck($columns);
    }

    public static function find($id)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->find($id);
    }

    public static function first()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->first();
    }

    public static function firstOrFail()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->firstOrFail();
    }

    public static function paginate($limit)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->paginate($limit);
    }

    public static function simplePaginate($limit)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden))->simplePaginate($limit);
    }
}