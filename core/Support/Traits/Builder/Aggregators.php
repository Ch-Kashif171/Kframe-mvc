<?php

namespace Core\Support\Traits\Builder;

trait Aggregators
{
    public static function increment($column, $value = 1)
    {
        $instance = new static();
        return $instance->doctrine->increment($column, $value);
    }

    public static function decrement($column, $value = 1)
    {
        $instance = new static();
        return $instance->doctrine->decrement($column, $value);
    }

    public static function sum($column)
    {
        $instance = new static();
        return $instance->doctrine->sum($column);
    }

    public static function max($column)
    {
        $instance = new static();
        return $instance->doctrine->max($column);
    }
    
    public static function min($column)
    {
        $instance = new static();
        return $instance->doctrine->min($column);
    }

    public static function count()
    {
        $instance = new static();
        return $instance->doctrine->count();
    }

    public static function exists()
    {
        $instance = new static();
        return $instance->doctrine->exists();
    }

}