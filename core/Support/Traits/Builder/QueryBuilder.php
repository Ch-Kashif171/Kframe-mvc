<?php

namespace Core\Support\Traits\Builder;

use Core\Database\Doctrine;
use function getTable;

trait QueryBuilder
{
    use Aggregators, Clauses, Statements, Joins;

    public $table;
    public $hide_fields;
    public $doctrine;

    public function __construct()
    {
        /*if table not define in model, then by default, model
         *name should be then table name*/
        if(is_null($this->table)) {
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

    public static function orderBy($field, $order = 'ASC')
    {
        $instance = new static();
        return $instance->doctrine->orderBy($field, $order);
    }

    public static function orderByDesc($field)
    {
        $instance = new static();
        return $instance->doctrine->orderByDesc($field);
    }

    public static function groupBy($fields)
    {
        $instance = new static();
        return $instance->doctrine->groupBy($fields);
    }

    public static function limit($limit)
    {
        $instance = new static();
        return $instance->doctrine->limit($limit);
    }

    public static function offset($offset)
    {
        $instance = new static();
        return $instance->doctrine->offset($offset);
    }

    public static function take($take)
    {
        $instance = new static();
        return $instance->doctrine->take($take);
    }

}