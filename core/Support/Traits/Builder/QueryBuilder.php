<?php

namespace Core\Support\Traits\Builder;

use Core\Database\Doctrine;
use function getTable;

trait QueryBuilder
{
    use Aggregators, Clauses, Statements, Joins;

    protected $table;
    protected $hide_fields;
    protected $doctrine;

    public function __construct()
    {
        /*if table not define in model, then by default, model
         *name should be then table name*/
        if(empty($this->table)) {
            $this->table = getTable(static::class);
        }

        $this->doctrine = new Doctrine($this->table,$this->hide_fields);
    }

    public function hideFields()
    {
        return $this->hide_fields;
    }

    public function table()
    {
        return $this->table;
    }

    public function orderBy($field, $order = 'ASC')
    {
        return $this->doctrine->orderBy($field, $order);
    }

    public function orderByDesc($field)
    {
        return $this->doctrine->orderByDesc($field);
    }

    public function groupBy($fields)
    {
        return $this->doctrine->groupBy($fields);
    }

    public function limit($limit)
    {
        return $this->doctrine->limit($limit);
    }

    public function offset($offset)
    {
        return $this->doctrine->offset($offset);
    }

    public function take($take)
    {
        return $this->doctrine->take($take);
    }
}