<?php

namespace Core\Support;

use Core\Database\QueryBuilder;
use Core\Exception\Handlers\DBException;
use Whoops\Exception\ErrorException;

/**
 * Class DB
 */
class DB
{
    /**
     * @param $table
     * @return QueryBuilder
     * @throws DBException
     */
    public static function table($table): QueryBuilder
    {
        return new QueryBuilder($table);
    }

    /**
     * @param $sql
     * @return bool
     * @throws ErrorException
     */
    public static function rawQuery($sql)
    {
        return QueryBuilder::rawQuery($sql);
    }

}
