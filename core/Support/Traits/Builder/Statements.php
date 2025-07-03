<?php

namespace Core\Support\Traits\Builder;

trait Statements
{
    public static function all()
    {
        $instance = new static();
        return $instance->doctrine->get();
    }

    public static function get()
    {
        $instance = new static();
        return $instance->doctrine->get();
    }

    public static function pluck($column)
    {
        $instance = new static();
        return $instance->doctrine->pluck($column);
    }

    public static function find($id)
    {
        $instance = new static();
        return $instance->doctrine->find($id);
    }

    public static function first()
    {
        $instance = new static();
        return $instance->doctrine->first();
    }

    public static function firstOrFail()
    {
        $instance = new static();
        return $instance->doctrine->firstOrFail();
    }

    public static function latest($column)
    {
        $instance = new static();
        return $instance->doctrine->latest($column);
    }

    public static function oldest($column)
    {
        $instance = new static();
        return $instance->doctrine->oldest($column);
    }

    public static function paginate($limit)
    {
        $instance = new static();
        return $instance->doctrine->paginate($limit);
    }

    public static function simplePaginate($limit)
    {
        $instance = new static();
        return $instance->doctrine->simplePaginate($limit);
    }

    public static function insert($data)
    {
        $instance = new static();
        return $instance->doctrine->insert($data);
    }

    public static function insertGetId($data)
    {
        $instance = new static();
        return $instance->doctrine->insertGetId($data);
    }

    public static function select($fields)
    {
        $instance = new static();
        return $instance->doctrine->select($fields);
    }

    public static function update($fields)
    {
        $instance = new static();
        return $instance->doctrine->update($fields);
    }

    public static function delete()
    {
        $instance = new static();
        return $instance->doctrine->delete();
    }

    public static function updateOrCreate($attributes, $values)
    {
        $instance = new static();
        return $instance->doctrine->updateOrCreate($attributes, $values);
    }

    public static function create($attributes)
    {
        $instance = new static();
        return $instance->doctrine->create($attributes);
    }

}