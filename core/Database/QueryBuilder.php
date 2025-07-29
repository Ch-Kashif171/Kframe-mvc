<?php

namespace Core\Database;


use Core\Exception\Handlers\DBException;
use Core\Support\Traits\Builder\Aggregators;
use Core\Support\Traits\Builder\EagerLoading;
use Core\Support\Traits\Builder\Getters;
use Core\Support\Traits\Builder\MakeResult;
use Whoops\Exception\ErrorException;

class QueryBuilder implements QueryBuilderInterface
{
    use MakeResult, Getters, Aggregators, EagerLoading;

    protected Doctrine $doctrine;
    protected $hidden = [];
    protected $modelClass;
    protected array $with = [];
    /**
     * @param $table
     * @param $hidden
     * @param $modelClass
     * @throws DBException
     */
    public function __construct($table, $hidden = null, $modelClass = null)
    {
        $this->doctrine = new Doctrine($table);
        $this->hidden = $hidden;
        $this->modelClass = $modelClass;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function where($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->where($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param ...$fields
     * @return QueryBuilderInterface
     */
    public function select(...$fields): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->select(...$fields);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $field
     * @param string $order
     * @return QueryBuilderInterface
     */
    public function orderBy($field, string $order = 'ASC'): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderBy($field, $order);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $field
     * @return QueryBuilderInterface
     */
    public function orderByDesc($field): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderByDesc($field);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $limit
     * @return QueryBuilderInterface
     */
    public function limit($limit): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->limit($limit);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function latest($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderByDesc($column);
        return $this;
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function oldest($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orderBy($column);
        return $this;
    }

    /**
     * @param $fields
     * @return QueryBuilderInterface
     */
    public function groupBy($fields): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->groupBy($fields);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $take
     * @return QueryBuilderInterface
     */
    public function take($take): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->take($take);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $offset
     * @return QueryBuilderInterface
     */
    public function offset($offset): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->offset($offset);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function orWhere($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->orWhere($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return QueryBuilderInterface
     */
    public function whereIn($column, array $values): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereIn($column, $values);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function whereNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNull($column);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @return QueryBuilderInterface
     */
    public function whereNotNull($column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->whereNotNull($column);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return QueryBuilderInterface
     */
    public function having($column, $operator, $value): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->having($column, $operator, $value);
        // Ensure modelClass is preserved
        return $this;
    }

    /**
     * @param $data
     * @return bool
     * @throws ErrorException
     */
    public function insert($data): bool
    {
        return $this->doctrine->insert($data);
    }

    /**
     * @param $data
     * @return string
     * @throws ErrorException
     */
    public function insertGetId($data)
    {
        return $this->doctrine->insertGetId($data);
    }

    /**
     * @param $fields
     * @return bool
     * @throws ErrorException
     */
    public function update($fields): bool
    {
        return $this->doctrine->update($fields);
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function delete(): bool
    {
        return $this->doctrine->delete();
    }

    /**
     * @param $attributes
     * @param $values
     * @return mixed
     */
    public function updateOrCreate($attributes, $values): mixed
    {
        return $this->doctrine->updateOrCreate($attributes, $values);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes): mixed
    {
        return $this->doctrine->create($attributes);
    }

    /**
     * @param $sql
     * @return bool
     * @throws ErrorException
     */
    public static function rawQuery($sql)
    {
        $doctrine = new Doctrine();
        return $doctrine->rawQuery($sql);
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function join($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->join($table, $column, $equal, $second_column);
        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function leftJoin($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->leftJoin($table, $column, $equal, $second_column);
        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function rightJoin($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->rightJoin($table, $column, $equal, $second_column);
        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return QueryBuilderInterface
     */
    public function fullOuterJoin($table, $column, $equal, $second_column): QueryBuilderInterface
    {
        $this->doctrine = $this->doctrine->fullOuterJoin($table, $column, $equal, $second_column);
        return $this;
    }

} 