<?php

namespace Core\Support\Traits\Builder;

trait Joins
{
    public static function join($table, $column, $equal, $second_column)
    {
        $instance = new static();
        return $instance->doctrine->join($table, $column, $equal, $second_column);
    }

    public static function leftJoin($table, $column, $equal, $second_column)
    {
        $instance = new static();
        return $instance->doctrine->leftJoin($table, $column, $equal, $second_column);
    }

}