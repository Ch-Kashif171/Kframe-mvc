<?php

namespace Core\Support\Traits\Builder;

use Core\Database\Doctrine;
use function getTable;

trait Builder
{
    use Clauses, Statements, Joins;

    protected $table;
    protected $hidden = [];
    protected $doctrine;

    public function __construct()
    {
        /**
         * if table not define in model, then by default, model
         * name should be then table name
         */
        if(empty($this->table)) {
            $this->table = getTable(static::class);
        }

        $this->doctrine = new Doctrine($this->table, $this->hidden);
    }

    public function hideFields()
    {
        return $this->hidden;
    }

    public function table()
    {
        return $this->table;
    }

    public function groupBy($fields)
    {
        return $this->doctrine->groupBy($fields);
    }

    public function take($take)
    {
        return $this->doctrine->take($take);
    }
}