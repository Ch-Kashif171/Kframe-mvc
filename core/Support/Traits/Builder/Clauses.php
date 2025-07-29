<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;
use Core\Database\QueryBuilderInterface;
use Core\Exception\Handlers\DBException;

trait Clauses
{
    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function where($column, $condition, $value): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->where($column, $condition, $value);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function orWhere($column, $condition, $value): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->orWhere($column, $condition, $value);
    }

    /**
     * @param $column
     * @param $value
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function whereIn($column, $value): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->whereIn($column, $value);
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function whereNull($column): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->whereNull($column);
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function whereNotNull($column): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->whereNotNull($column);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function having($column, $condition, $value): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->having($column, $condition, $value);
    }
}