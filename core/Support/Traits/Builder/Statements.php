<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;
use Core\Database\QueryBuilderInterface;
use Core\Exception\Handlers\DBException;
use Whoops\Exception\ErrorException;

trait Statements
{
    /**
     * @param string $column
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function latest(string $column = 'created_at'): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->latest($column);
    }

    /**
     * @param string $column
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function oldest(string $column = 'created_at'): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->oldest($column);
    }

    /**
     * @param $data
     * @return bool
     * @throws DBException
     * @throws ErrorException
     */
    public static function insert($data): bool
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->insert($data);
    }

    /**
     * @param $data
     * @return string
     * @throws DBException
     * @throws ErrorException
     */
    public static function insertGetId($data): string
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->insertGetId($data);
    }

    /**
     * @param ...$fields
     * @return QueryBuilderInterface
     * @throws DBException
     */
    public static function select(...$fields): QueryBuilderInterface
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->select(...$fields);
    }

    /**
     * @param $fields
     * @return bool
     * @throws DBException
     * @throws ErrorException
     */
    public static function update($fields): bool
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->update($fields);
    }

    /**
     * @return bool
     * @throws DBException
     * @throws ErrorException
     */
    public static function delete(): bool
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->delete();
    }

    /**
     * @param $attributes
     * @param $values
     * @return mixed
     * @throws DBException
     */
    public static function updateOrCreate($attributes, $values): mixed
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->updateOrCreate($attributes, $values);
    }

    /**
     * @param $attributes
     * @return mixed
     * @throws DBException
     */
    public static function create($attributes): mixed
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->hidden, static::class))->create($attributes);
    }
}