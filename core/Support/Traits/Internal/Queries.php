<?php

namespace Core\Support\Traits\Internal;

use Core\Database\Connection\Database;
use Core\Database\Doctrine;
use Whoops\Exception\ErrorException;

trait Queries
{
    public $con;
    protected $table;
    public $statement;
    public $where_statement;
    public $where;
    public $fields;
    protected $hide_fields;
    public $result;
    public $exception;

    /**
     * @throws \Core\Exception\Handlers\DBException
     */
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
        foreach ($data as $column => $value) {
            if($count == 1 && $this->where_statement == '') {
                $this->where_statement .= " WHERE ".$column." = '".$value."' ";
            } else {
                $this->where_statement .= " AND ".$column." = '".$value."' ";
            }

            $count++;
        }

        return $this;
    }

    public function userFound()
    {
        $sql = "SELECT * FROM {$this->table} {$this->where_statement}";
        $query = $this->con->query($sql);
        $this->result = $query->fetch(\PDO::FETCH_OBJ);
        return $this->result;
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
        if (!empty($this->joins)) {
            $tables = $this->getJoinedTables($table);
            $columns = [];
            foreach ($tables as $tbl) {
                $skipFields = ($tbl === $table && !is_null($this->hide_fields)) ? explode(',', $this->hide_fields) : [];
                $columns = array_merge($columns, $this->getAliasedColumns($tbl, $skipFields));
            }
            return implode(',', $columns);
        }
        // No joins: just get columns for main table
        $skipFields = !is_null($this->hide_fields) ? explode(',', $this->hide_fields) : [];
        return implode(',', $this->getTableColumns($table, $skipFields));
    }

    /**
     * Get all joined table names, including the main table
     * @param string $mainTable
     * @return array
     */
    private function getJoinedTables($mainTable)
    {
        $tables = [$mainTable];
        if (preg_match_all('/JOIN\s+([a-zA-Z0-9_]+)/', $this->joins, $matches)) {
            foreach ($matches[1] as $joinedTable) {
                $tables[] = $joinedTable;
            }
        }
        return $tables;
    }

    /**
     * Get columns for a table, skipping any in $skipFields
     * @param string $table
     * @param array $skipFields
     * @return array
     */
    private function getTableColumns($table, $skipFields = [])
    {
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$table."' ";
        $fields = $this->rawQuery($query);
        if ($fields === false) {
            return [];
        }
        $columns = [];
        foreach ($fields as $field) {
            if (!in_array($field->COLUMN_NAME, $skipFields)) {
                $columns[] = $field->COLUMN_NAME;
            }
        }
        return $columns;
    }

    /**
     * Get aliased columns for a table (table.column AS table_column), skipping any in $skipFields
     * @param string $table
     * @param array $skipFields
     * @return array
     */
    private function getAliasedColumns($table, $skipFields = [])
    {
        $columns = [];
        foreach ($this->getTableColumns($table, $skipFields) as $col) {
            $alias = $table . '_' . $col;
            $columns[] = "$table.$col AS $alias";
        }
        return $columns;
    }
}