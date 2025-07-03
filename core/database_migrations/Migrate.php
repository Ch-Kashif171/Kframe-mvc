<?php
namespace Core\database_migrations;

use Core\Database\Doctrine;
use Core\database_migrations\Schema;
use Core\database_migrations\RecordMigration;

class Migrate
{
    use RecordMigration;

    public $table;
    public function __construct()
    {
        $this->table = new Blueprint();
    }

    /**
     * @param $table
     * @param $fields
     */
    public static function create($table,$fields) {

        $exist = RecordMigration::existTable($table);

        if (!$exist) {
            $query = "CREATE TABLE {$table}(";
            $field_statements = '';
            foreach ($fields as $field) {
                $field_statements .= $field->statement . ',';
            }
            $statement = rtrim($field_statements, ',');
            $query .= $statement . " );";
            $doctrine = new Doctrine();
            $success = $doctrine->rawQuery($query, true);
            if ($success) {
                echo "{$table} table has been successfully created \n";

                RecordMigration::saveMigration($table);
            }

        } else {
            echo "{$table} table already exist \n";
        }
    }
}