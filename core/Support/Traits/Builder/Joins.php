<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;

trait Joins
{
    public static function join($table, $column, $equal, $second_column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->join($table, $column, $equal, $second_column);
    }

    public static function leftJoin($table, $column, $equal, $second_column)
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hide_fields))->leftJoin($table, $column, $equal, $second_column);
    }
}