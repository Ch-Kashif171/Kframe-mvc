<?php
namespace Core\database_migrations;

use Closure;
use Core\Doctrine;
use Core\database_migrations\Schema;

require_once __DIR__.'/../../config/app.php';

class Migrate
{
    public $table;
    public function __construct()
    {
        $this->table = new Blueprint();
    }

    /**
     * @param $table
     * @param $fields
     */
    public static function create_old($table,$fields){

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

    public static function create($table, Closure $closure){
        $closure(new Schema($table));

    }
}