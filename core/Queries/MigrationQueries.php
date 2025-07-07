<?php
namespace Core\Queries;

class MigrationQueries
{
    public const CREATE_MIGRATIONS_TABLE = "CREATE TABLE IF NOT EXISTS `migrations` (
        `id` int(11) NOT NULL AUTO_INCREMENT, primary key (id),
        `migration` varchar(255) NOT NULL,
        `is_migrate` varchar(255) NOT NULL
    );";

    public static function selectByName($name)
    {
        return "SELECT * from migrations where migration = '" . addslashes($name) . "'";
    }

    public static function selectAll()
    {
        return "SELECT * FROM migrations ORDER BY id DESC LIMIT 1";
    }

    public static function insert($name)
    {
        return "INSERT INTO migrations (migration, is_migrate) VALUES ('" . addslashes($name) . "','1')";
    }

    public static function deleteById($id)
    {
        return "DELETE FROM migrations WHERE id = " . (int)$id;
    }

    public static function tableExists($db_name, $table)
    {
        return "SELECT * FROM information_schema.tables WHERE table_schema = '" . addslashes($db_name) . "' AND table_name = '" . addslashes($table) . "' ";
    }
} 