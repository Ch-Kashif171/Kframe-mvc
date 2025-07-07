<?php

namespace Core\Migrations;
use Core\Database\Doctrine;
use Core\Queries\MigrationQueries;

trait RecordMigration
{

    public static function existTable($migration_name) {
        $db_name = config("database.db_database");
        $doctrine = new Doctrine();
        $query = MigrationQueries::tableExists($db_name, 'migrations');
        $exist = $doctrine->rawQuery($query);
        if (!$exist) $exist = [];
        if (count($exist) > 0) {
            $query = MigrationQueries::selectByName($migration_name);
            $result = $doctrine->rawQuery($query);
            if (!$result) $result = [];
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function saveMigration($migration_name)
    {
        // Only save if migration_name matches migration file pattern
        $pattern = '/^\d{4}_\d{2}_\d{2}_\d{6}_.+\.php$/i';
        if (!preg_match($pattern, $migration_name)) {
            return;
        }
        $doctrine = new Doctrine();
        $doctrine->rawQuery(MigrationQueries::CREATE_MIGRATIONS_TABLE, true);
        $check = $doctrine->rawQuery(MigrationQueries::selectByName($migration_name));
        if (!$check) $check = [];
        if (count($check) == 0) {
            $migrate = MigrationQueries::insert($migration_name);
            $doctrine->rawQuery($migrate, true);
        }
    }

}