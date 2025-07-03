<?php
namespace Core\Support;

use Core\Database\QueryBuilder;
use Core\Database\Doctrine;

/**
 * Class DB
 */
class DB
{
    public function __construct()
    {

    }

    /**
     * @param null $table
     * @return QueryBuilder
     */
    public static function table($table = null)
    {
        /*here we can also set hidden_fields but currently not working*/
        return new QueryBuilder($table);
    }

    /**
     * @param $sql
     * @return array|bool
     * @throws \Whoops\Exception\ErrorException
     */
    public static function rawQuery($sql)
    {
        $doctrine = new Doctrine();
        $result = $doctrine->rawQuery($sql);
        return $result;
    }
}