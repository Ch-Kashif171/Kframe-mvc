<?php

namespace Core\Migrations;

use Core\Support\DB;

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
     * @param $fieldsOrCallback
     */
    public static function create($table, $fieldsOrCallback, $migrationName = null) {
        // Always use the migration file name (with .php extension) for tracking
        if ($migrationName === null) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $migrationName = isset($bt[1]['file']) ? basename($bt[1]['file']) : $table;
        }
        // Check if table exists in the database
        $db_name = config("database.db_database");
        $tableExistQuery = "SELECT * FROM information_schema.tables WHERE table_schema = '".$db_name."' AND table_name = '".$table."' ";
        $tableExist = DB::rawQuery($tableExistQuery);
        if ($tableExist && count($tableExist) > 0) {
            echo "{$table} table already exist \n";
            return;
        }
        $query = "CREATE TABLE {$table}(";
        $field_statements = '';
        $fields = [];
        if (is_callable($fieldsOrCallback)) {
            $blueprint = new Blueprint();
            $fieldsOrCallback($blueprint);
            $fields = $blueprint->columns;
        } else {
            $fields = $fieldsOrCallback;
        }
        foreach ($fields as $field) {
            $field_statements .= $field->statement . ',';
        }
        $statement = rtrim($field_statements, ',');
        $query .= $statement . " );";
        $success = DB::rawQuery($query, true);
        if ($success) {
            echo "{$table} table has been successfully created \n";
            self::saveMigration($migrationName);
        }
    }

    /**
     * Drop a table if it exists
     * @param string $table
     */
    public static function drop($table)
    {
        $query = "DROP TABLE IF EXISTS {$table};";
        try {
            DB::rawQuery($query, true);
            echo "{$table} table has been dropped successfully\n";
        } catch (\Exception $e) {
            echo "Failed to drop {$table} table or it does not exist\n";
        }
    }

    /**
     * Drop a table only if it exists (no warning if not present)
     * @param string $table
     */
    public static function dropIfExists($table)
    {
        $db_name = config('database.db_database');
        $tableExistQuery = "SELECT * FROM information_schema.tables WHERE table_schema = '".$db_name."' AND table_name = '".$table."' ";
        $tableExist = \Core\Support\DB::rawQuery($tableExistQuery);
        if ($tableExist && count($tableExist) > 0) {
            self::drop($table);
        }
    }
}