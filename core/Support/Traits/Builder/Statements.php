<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Statements
{

    public static function latest($column = 'created_at')
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->latest($column);
    }

    public static function oldest($column = 'created_at')
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->oldest($column);
    }

    public static function insert($data)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->insert($data);
    }

    public static function insertGetId($data)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->insertGetId($data);
    }

    public static function select(...$fields)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->select(...$fields);
    }

    public static function update($fields)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->update($fields);
    }

    public static function delete()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->delete();
    }

    public static function updateOrCreate($attributes, $values)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->updateOrCreate($attributes, $values);
    }

    public static function create($attributes)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->create($attributes);
    }
}