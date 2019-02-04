<?php

namespace Core\database_migrations;


class Schema
{
    public $table;
    public $fields;

    public function __construct($table = null , $fields = null)
    {
        $this->table = $table;
        $this->fields = $fields;

    }

    public function create($table, $fields){

        $query = "CREATE TABLE {$table}(";
        $field_statements = '';
        foreach ($fields as $field) {
            $field_statements .= $field->statement.',';
        }
        $statement = rtrim($field_statements,',');
        $query .= $statement." );";
        $doctrine = new Doctrine();
        $success = $doctrine->rawQuery($query,true);
        if($success){
            echo "{$table} table has been successfully created \n";
        }
    }


}