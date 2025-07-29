<?php

namespace Core\Database;

use Core\Support\Collection\Collection;

interface QueryBuilderInterface
{
    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function where($column, $operator, $value): QueryBuilderInterface;

    /**
     * @param ...$fields
     * @return QueryBuilderInterface
     */
    public function select(...$fields): QueryBuilderInterface;

    /**
     * @return array|\Core\Support\Collection\Collection
     */
    public function all(): array|Collection;

    /**
     * @return array|Collection
     */
    public function get(): array|Collection;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function firstOrFail();

    /**
     * @return bool
     */
    public function exists(): bool;

    /**
     * @param $field
     * @param string $order
     * @return QueryBuilderInterface
     */
    public function orderBy($field, string $order = 'ASC'): QueryBuilderInterface;

    /**
     * @param $field
     * @return QueryBuilderInterface
     */
    public function orderByDesc($field): QueryBuilderInterface;

    /**
     * @param $limit
     * @return QueryBuilderInterface
     */
    public function limit($limit): QueryBuilderInterface;

    /**
     * @param string $column
     * @return int
     */
    public function count(string $column = "*"): int;

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column);

    /**
     * @param $column
     * @return mixed
     */
    public function max($column);

    /**
     * @param $column
     * @return mixed
     */
    public function min($column);

    /**
     * @param $columns
     * @return array
     */
    public function pluck($columns): array;

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function latest($column): QueryBuilderInterface;

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function oldest($column): QueryBuilderInterface;

    /**
     * @param $fields
     * @return QueryBuilderInterface
     */
    public function groupBy($fields): QueryBuilderInterface;

    /**
     * @param $take
     * @return QueryBuilderInterface
     */
    public function take($take): QueryBuilderInterface;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function orWhere($column, $operator, $value): QueryBuilderInterface;

    /**
     * @param $column
     * @param array $values
     * @return QueryBuilderInterface
     */
    public function whereIn($column, array $values): QueryBuilderInterface;

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function whereNull($column): QueryBuilderInterface;

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function whereNotNull($column): QueryBuilderInterface;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function having($column, $operator, $value): QueryBuilderInterface;

    /**
     * @param $limit
     * @return mixed
     */
    public function paginate($limit);

    /**
     * @param $limit
     * @return array|\Core\Support\Collection\Collection
     */
    public function simplePaginate($limit): array|Collection;

    /**
     * @param $data
     * @return bool
     */
    public function insert($data): bool;

    /**
     * @param $data
     * @return mixed
     */
    public function insertGetId($data);

    /**
     * @param $fields
     * @return bool
     */
    public function update($fields): bool;

    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @param $attributes
     * @param $values
     * @return mixed
     */
    public function updateOrCreate($attributes, $values);

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param $column
     * @param int $value
     * @return bool
     */
    public function increment($column, int|string $value = 1): bool;

    /**
     * @param $column
     * @param int|string $value
     * @return bool
     */
    public function decrement($column, int|string $value = 1): bool;

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function join($table, $column, $equal, $second_column): QueryBuilderInterface;

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function leftJoin($table, $column, $equal, $second_column): QueryBuilderInterface;

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function rightJoin($table, $column, $equal, $second_column): QueryBuilderInterface;

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function fullOuterJoin($table, $column, $equal, $second_column): QueryBuilderInterface;

    /**
     * @param $relations
     * @return $this
     */
    public function with($relations): static;

    /**
     * @param $relation
     * @return $this
     */
    public function has($relation): static;

    /**
     * @param $relation
     * @param $callback
     * @return $this
     */
    public function whereHas($relation, $callback = null): static;

    /**
     * @param $relation
     * @param $callback
     * @return $this
     */
    public function withWhereHas($relation, $callback = null): static;

}