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

        $exist = self::existTable($table);

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

                self::saveMigration($table);
            }

        } else {
            echo "{$table} table already exist \n";
        }
    }

    /**
     * Drop a table if it exists
     * @param string $table
     */
    public static function drop($table)
    {
        $query = "DROP TABLE IF EXISTS {$table};";
        $doctrine = new Doctrine();
        $success = $doctrine->rawQuery($query, true);
        if ($success) {
            echo "{$table} table has been dropped successfully\n";
        } else {
            echo "Failed to drop {$table} table or it does not exist\n";
        }
    }
}