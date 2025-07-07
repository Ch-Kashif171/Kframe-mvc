<?php

namespace Core\Migrations;
use Core\Database\Doctrine;

trait RecordMigration
{

    public static function existTable($migration_name) {

        $db_name = config("database.db_database");
        $doctrine = new Doctrine();
        $query = "SELECT * FROM information_schema.tables WHERE table_schema = '".$db_name."' 
        AND table_name = 'migrations' ";

        $exist = $doctrine->rawQuery($query);
        if (!$exist) $exist = [];

        if (count($exist) > 0) {

            $query = 'SELECT * from migrations where migration = "' . $migration_name . '"';
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
        $query = "CREATE TABLE IF NOT EXISTS `migrations` (
          `id` int(11) NOT NULL AUTO_INCREMENT, primary key (id),
          `migration` varchar(255) NOT NULL,
          `is_migrate` varchar(255) NOT NULL
          );";
        $doctrine = new Doctrine();
        $doctrine->rawQuery($query,true);
        $check = $doctrine->rawQuery('SELECT * from migrations where migration = "' . $migration_name . '"');
        if (!$check) $check = [];
        if (count($check) == 0) {
            $migrate = "INSERT INTO migrations (migration, is_migrate) VALUES ('".$migration_name."','1')";
            $doctrine->rawQuery($migrate,true);
        }
    }

}