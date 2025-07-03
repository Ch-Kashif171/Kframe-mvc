<?php

namespace Core\database_migrations;
use Core\Database\Doctrine;

trait RecordMigration
{

    public static function existTable($table_name) {

        $db_name = env("DB_DATABASE");
        $doctrine = new Doctrine();
        $query = "SELECT * FROM information_schema.tables WHERE table_schema = '".$db_name."' 
        AND table_name = 'migrations' ";

        $exist = $doctrine->rawQuery($query);


        if (count($exist) > 0) {

            $query = 'SELECT * from migrations where migration = "' . $table_name . '"';
            $result = $doctrine->rawQuery($query);

            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public static function saveMigration($table_name)
    {

        $query = "CREATE TABLE IF NOT EXISTS `migrations` (
          `id` int(11) NOT NULL AUTO_INCREMENT, primary key (id),
          `migration` varchar(255) NOT NULL,
          `is_migrate` varchar(255) NOT NULL
          );";

        $doctrine = new Doctrine();
        $doctrine->rawQuery($query,true);


        $migrate = "INSERT INTO migrations (migration, is_migrate) VALUES ('".$table_name."','1')";

        $doctrine->rawQuery($migrate,true);

    }

}