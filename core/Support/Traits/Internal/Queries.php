<?php

namespace Core\Support\Traits\Internal;

use Core\Database\connection\database;
use Core\Database\Doctrine;
use Whoops\Exception\ErrorException;

trait Queries
{
    public $con;
    public $table;
    public $statement;
    public $where;
    public $fields;
    public $hide_fields;
    public $result;
    public $exception;

    public function __construct($table = null, $hidden_fields = null, $statement = null, $fields = null)
    {
        $this->table  =   $table;
        $this->statement  =   $statement;
        $this->hide_fields = $hidden_fields;
        if(!is_null($fields)){
            $this->fields  =   is_null($this->hide_fields) ? $fields : str_replace($this->hide_fields,'', $fields);
        }else{
            $this->fields  =  $fields;
        }

        $db   =   new database();
        $this->con = $db->connection();
    }

    /**
     * @param $data
     * @return Doctrine
     */
    public function where_array($data)
    {
        $count = 1;
        $query = '';
        foreach ($data as $column=> $value){
            if($count == 1){
                $query .= " WHERE ".$column." = '".$value."' ";
            }else{
                $query .= " AND ".$column." = '".$value."' ";
            }
            $count++;
        }

        if ($query != ''){
            $this->statement .= $query;
        }

        return $this;
    }

    /**
     * @param $sql
     * @param $create
     * @return bool
     * @throws ErrorException
     */
    public function rawQuery($sql,$create = false)
    {

        try {
            $query = $this->con->query($sql);

            if($create){
                $result = true;
            }else{
                $result = $query->fetchAll(\PDO::FETCH_OBJ);
            }
            return $result;
        }
        catch (Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * @param $table
     * @return string
     * @throws ErrorException
     */
    private function get_table_columns_except_some($table)
    {
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$table."' ";

        $fields = $this->rawQuery($query);

        $columns = '';
        foreach ($fields as $key=> $field) {
            if (!is_null($this->hide_fields)) {
                $hidden_fields = explode(',',$this->hide_fields);
                if(!in_array($field->COLUMN_NAME,$hidden_fields)){
                    $columns .= $field->COLUMN_NAME.',';
                }
            }else{
                $columns .= $field->COLUMN_NAME.',';
            }

        }

        $select_columns = rtrim($columns,',');

        return $select_columns;
    }

}