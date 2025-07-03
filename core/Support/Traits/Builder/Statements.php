<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Statements
{
    public static function all()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->get();
    }

    public static function get()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->get();
    }

    public static function pluck($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->pluck($column);
    }

    public static function find($id)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->find($id);
    }

    public static function first()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->first();
    }

    public static function firstOrFail()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->firstOrFail();
    }

    public static function latest($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->latest($column);
    }

    public static function oldest($column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->oldest($column);
    }

    public static function paginate($limit)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->paginate($limit);
    }

    public static function simplePaginate($limit)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->simplePaginate($limit);
    }

    public static function insert($data)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->insert($data);
    }

    public static function insertGetId($data)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->insertGetId($data);
    }

    public static function select(...$fields)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->select(...$fields);
    }

    public static function update($fields)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->update($fields);
    }

    public static function delete()
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->delete();
    }

    public static function updateOrCreate($attributes, $values)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->updateOrCreate($attributes, $values);
    }

    public static function create($attributes)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->create($attributes);
    }
}