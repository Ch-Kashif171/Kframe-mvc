<?php
namespace Core\Database;

/**
 * Interface for the query builder wrapper.
 *
 * @method QueryBuilderInterface where($column, $operator, $value)
 * @method QueryBuilderInterface select(...$fields)
 * @method array get()
 * @method object|null first()
 * @method object|null firstOrFail()
 * @method bool exists()
 * @method QueryBuilderInterface orderBy($field, $order = 'ASC')
 * @method QueryBuilderInterface limit($limit)
 * @method int count()
 * @method float|int sum($column)
 * @method float|int max($column)
 * @method float|int min($column)
 * @method array pluck($column)
 * @method object|null find($id)
 * @method QueryBuilderInterface latest($column)
 * @method QueryBuilderInterface oldest($column)
 * @method QueryBuilderInterface groupBy($fields)
 * @method QueryBuilderInterface take($take)
 * @method QueryBuilderInterface orWhere($column, $operator, $value)
 * @method QueryBuilderInterface whereIn($column, array $values)
 * @method QueryBuilderInterface whereNull($column)
 * @method QueryBuilderInterface whereNotNull($column)
 * @method QueryBuilderInterface having($column, $operator, $value)
 * @method array paginate($limit)
 * @method array simplePaginate($limit)
 * @method bool insert($data)
 * @method int|string insertGetId($data)
 * @method bool update($fields)
 * @method bool delete()
 * @method object updateOrCreate($attributes, $values)
 * @method object create($attributes)
 * @method bool increment($column, $value = 1)
 * @method bool decrement($column, $value = 1)
 * @method QueryBuilderInterface join($table, $column, $equal, $second_column)
 * @method QueryBuilderInterface leftJoin($table, $column, $equal, $second_column)
 */
interface QueryBuilderInterface
{
    public function where($column, $operator, $value): QueryBuilderInterface;
    public function select(...$fields): QueryBuilderInterface;
    public function get(): array;
    public function first();
    public function firstOrFail();
    public function exists(): bool;
    public function orderBy($field, $order = 'ASC'): QueryBuilderInterface;
    public function limit($limit): QueryBuilderInterface;
    public function count(): int;
    public function sum($column);
    public function max($column);
    public function min($column);
    public function pluck($column): array;
    public function find($id);
    public function latest($column): QueryBuilderInterface;
    public function oldest($column): QueryBuilderInterface;
    public function groupBy($fields): QueryBuilderInterface;
    public function take($take): QueryBuilderInterface;
    public function orWhere($column, $operator, $value): QueryBuilderInterface;
    public function whereIn($column, array $values): QueryBuilderInterface;
    public function whereNull($column): QueryBuilderInterface;
    public function whereNotNull($column): QueryBuilderInterface;
    public function having($column, $operator, $value): QueryBuilderInterface;
    public function paginate($limit): array;
    public function simplePaginate($limit): array;
    public function insert($data): bool;
    public function insertGetId($data);
    public function update($fields): bool;
    public function delete(): bool;
    public function updateOrCreate($attributes, $values);
    public function create($attributes);
    public function increment($column, $value = 1): bool;
    public function decrement($column, $value = 1): bool;
    public function join($table, $column, $equal, $second_column): QueryBuilderInterface;
    public function leftJoin($table, $column, $equal, $second_column): QueryBuilderInterface;
    // Add more as needed
} 