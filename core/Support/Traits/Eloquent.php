<?php

namespace Core\Support\Traits;

use Core\connection\database;

trait Eloquent
{
    public static $conn;
    public static $tbl;
    public static $stat;
    public static $wheres;
    public static $all_fields;
    public static $hidden_fields;
    public static $results;

    public function __construct($table=null,$hidden_fields=null,$statement=null,$fields = null){
        //$this->exception = new ErrorsHandler();
        self::$tbl  =   $table;
        self::$stat  =   $statement;
        self::$hidden_fields = $hidden_fields;
        if(!is_null($fields)){
            self::$all_fields  =   str_replace( self::$hidden_fields,'',$fields);
        }else{
            self::$all_fields  =  $fields;
        }
        $db   =   new database();
        self::$conn = $db->connection();
    }

    public static function first(){}

    public static function last($column){
        $query = " order by {$column} DESC";
        self::$stat .= $query;
        return self::$stat;
    }

}