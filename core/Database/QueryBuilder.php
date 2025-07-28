<?php

namespace Core\Database;


class QueryBuilder implements QueryBuilderInterface
{
    use MakeResult;

    protected Doctrine $doctrine;
    protected $hidden = [];

    public function __construct($table, $hidden = null)
    {
        $this->doctrine = new Doctrine($table);
        $this->hidden = $hidden;
    }

    public function get(): array
    {
        $result = $this->doctrine->get();
        return self::getResult($result);
    }

    public function first()
    {
        $result = $this->doctrine->first();
        return self::skipHidden($result);
    }

    public function pluck($columns): array
    {
        return $this->doctrine->pluck($columns);
    }

    public function find($id)
    {
        $result = $this->doctrine->find($id);
        return self::getResult($result);
    }

    public function firstOrFail()
    {
        $result = $this->doctrine->firstOrFail();
        return self::getResult($result);
    }

    public function paginate($limit): array
    {
        $result = $this->doctrine->paginate($limit);
        return self::getResult($result);
    }

    public function simplePaginate($limit): array
    {
        $result = $this->doctrine->simplePaginate($limit);
        return self::getResult($result);
    }

    public function where($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->where($column, $operator, $value);
        return $this;
    }

    public function select(...$fields): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->select(...$fields);
        return $this;
    }

    public function exists(): bool
    {
        return $this->doctrine->exists();
    }

    public function orderBy($field, $order = 'ASC'): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderBy($field, $order);
        return $this;
    }

    public function orderByDesc($field): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderByDesc($field);
        return $this;
    }

    public function limit($limit): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->limit($limit);
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
        return $this;
    }

    public function take($take): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->take($take);
        return $this;
    }

    public function offset($offset): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->offset($offset);
        return $this;
    }

    public function orWhere($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orWhere($column, $operator, $value);
        return $this;
    }

    public function whereIn($column, array $values): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereIn($column, $values);
        return $this;
    }

    public function whereNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNull($column);
        return $this;
    }

    public function whereNotNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNotNull($column);
        return $this;
    }

    public function having($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->having($column, $operator, $value);
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