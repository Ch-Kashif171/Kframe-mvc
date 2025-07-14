<?php

namespace Core\Support\Traits\Internal;

use Core\Database\connection\database;
use Core\Database\Doctrine;
use Whoops\Exception\ErrorException;

trait Queries
{
    public $con;
    protected $table;
    public $statement;
    public $where;
    public $fields;
    protected $hide_fields;
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
        catch (\Exception $e){
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
        // If joins are present, build aliased select list for all tables
        if (!empty($this->joins)) {
            // Extract all table names: main table + joined tables
            $tables = [$table];
            // Match all table names in JOIN clauses (e.g., 'JOIN users ON ...')
            if (preg_match_all('/JOIN\s+([a-zA-Z0-9_]+)/', $this->joins, $matches)) {
                foreach ($matches[1] as $joinedTable) {
                    $tables[] = $joinedTable;
                }
            }
            $columns = [];
            foreach ($tables as $tbl) {
                $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$tbl."' ";
                $fields = $this->rawQuery($query);
                if ($fields === false) continue;
                foreach ($fields as $field) {
                    // Optionally skip hidden fields for main table
                    if ($tbl === $table && !is_null($this->hide_fields)) {
                        $hidden_fields = explode(',', $this->hide_fields);
                        if (in_array($field->COLUMN_NAME, $hidden_fields)) {
                            continue;
                        }
                    }
                    $alias = $tbl . '_' . $field->COLUMN_NAME;
                    $columns[] = "$tbl.{$field->COLUMN_NAME} AS $alias";
                }
            }
            return implode(',', $columns);
        }
        // No joins: keep existing behavior
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$table."' ";
        $fields = $this->rawQuery($query);
        if ($fields === false) return '';
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