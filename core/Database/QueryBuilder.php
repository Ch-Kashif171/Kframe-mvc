<?php

namespace Core\Database;


use Core\Support\Traits\Builder\Getters;

class QueryBuilder implements QueryBuilderInterface
{
    use MakeResult, Getters;

    protected Doctrine $doctrine;
    protected $hidden = [];
    protected $modelClass;

    public function __construct($table, $hidden = null, $modelClass = null)
    {
        $this->doctrine = new Doctrine($table);
        $this->hidden = $hidden;
        $this->modelClass = $modelClass;
    }

    public function where($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->where($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    public function select(...$fields): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->select(...$fields);
        // Ensure modelClass is preserved
        return $this;
    }

    public function exists(): bool
    {
        return $this->doctrine->exists();
    }

    public function orderBy($field, $order = 'ASC'): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderBy($field, $order);
        // Ensure modelClass is preserved
        return $this;
    }

    public function orderByDesc($field): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderByDesc($field);
        // Ensure modelClass is preserved
        return $this;
    }

    public function limit($limit): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->limit($limit);
        // Ensure modelClass is preserved
        return $this;
    }

    public function count($column = "*"): int
    {
        return $this->doctrine->count($column);
    }

    public function sum($column)
    {
        return $this->doctrine->sum($column);
    }

    public function max($column)
    {
        return $this->doctrine->max($column);
    }

    public function min($column)
    {
        return $this->doctrine->min($column);
    }

    public function latest($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderByDesc($column);
        return $this;
    }

    public function oldest($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderBy($column);
        return $this;
    }

    public function groupBy($fields): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->groupBy($fields);
        // Ensure modelClass is preserved
        return $this;
    }

    public function take($take): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->take($take);
        // Ensure modelClass is preserved
        return $this;
    }

    public function offset($offset): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->offset($offset);
        // Ensure modelClass is preserved
        return $this;
    }

    public function orWhere($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orWhere($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    public function whereIn($column, array $values): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereIn($column, $values);
        // Ensure modelClass is preserved
        return $this;
    }

    public function whereNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNull($column);
        // Ensure modelClass is preserved
        return $this;
    }

    public function whereNotNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNotNull($column);
        // Ensure modelClass is preserved
        return $this;
    }

    public function having($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->having($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    public function insert($data): bool
    {
        return $this->doctrine->insert($data);
    }

    public function insertGetId($data)
    {
        return $this->doctrine->insertGetId($data);
    }

    public function update($fields): bool
    {
        return $this->doctrine->update($fields);
    }

    public function delete(): bool
    {
        return $this->doctrine->delete();
    }

    public function updateOrCreate($attributes, $values)
    {
        return $this->doctrine->updateOrCreate($attributes, $values);
    }

    public function create($attributes)
    {
        return $this->doctrine->create($attributes);
    }

    public function increment($column, $value = 1): bool
    {
        return $this->doctrine->increment($column, $value);
    }

    public function decrement($column, $value = 1): bool
    {
        return $this->doctrine->decrement($column, $value);
    }

    public function join($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->join($table, $column, $equal, $second_column);
        return $this;
    }

    public function leftJoin($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->leftJoin($table, $column, $equal, $second_column);
        return $this;
    }

} 