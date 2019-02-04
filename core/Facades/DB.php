<?php
namespace Core\Facades;

use Core\Doctrine;

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
     * @return Doctrine
     */
    public static function table($table = null){
        /*here we can also set hidden_fields but currently not working*/
        return new Doctrine($table);
    }

    /**
     * @param $sql
     * @return array|bool
     */
    public static function rawQuery($sql){
        $doctrine = self::table();
        $result = $doctrine->rawQuery($sql) ;
        return $result;
    }
}