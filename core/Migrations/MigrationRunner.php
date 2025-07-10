<?php
namespace Core\Migrations;

use Core\Database\Doctrine;
use Core\Queries\MigrationQueries;
use Core\Support\DB;

/**
 * Class MigrationRunner
 * Handles migration file discovery, execution, and rollback logic for the migration system.
 */
class MigrationRunner
{
    use RecordMigration;

    /**
     * @var string Directory where migration files are stored.
     */
    protected $migrationsDir;

    /**
     * MigrationRunner constructor.
     * @param string $migrationsDir
     */
    public function __construct($migrationsDir = 'migrations/')
    {
        $this->migrationsDir = $migrationsDir;
        $this->ensureMigrationsTable();
    }

    /**
     * Run all pending migrations in order.
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function runAll($output)
    {
        DB::rawQuery(MigrationQueries::CREATE_MIGRATIONS_TABLE, true);
        $files = $this->findMigrationFiles();
        $ran = 0;
        foreach ($files as $file) {
            $migrationName = basename($file);
            if ($this->hasRun($migrationName)) {
                $output->writeln("<info>Already migrated: $migrationName</info>");
                continue;
            }
            $output->writeln("<comment>Migrating: $migrationName</comment>");
            $migrationClass = $this->loadMigrationClass($file);
            if (!$migrationClass) {
                $output->writeln("<error>No migration class with an up() method found in $migrationName.</error>");
                continue;
            }
            $migration = new $migrationClass();
            $migration->up();
            self::saveMigration($migrationName);
            $output->writeln("<info>Migrated: $migrationName</info>");
            $ran++;
        }
        if ($ran === 0) {
            $output->writeln('<info>No new migrations to run.</info>');
        }
    }

    /**
     * Rollback the last run migration.
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function rollbackLast($output)
    {
        $latest = DB::rawQuery(MigrationQueries::selectAll());
        if (!$latest) $latest = [];
        if (count($latest) === 0) {
            $output->writeln('<error>No migrations to rollback.</error>');
            return;
        }
        $migrationName = $latest[0]->migration;
        $output->writeln(["Rolling back migration: $migrationName"]);
        // Try to find the migration file
        $migrationFile = null;
        foreach (glob($this->migrationsDir . '*.php') as $file) {
            if (basename($file) === $migrationName || basename($file, '.php') === $migrationName) {
                $migrationFile = $file;
                break;
            }
        }
        if (!$migrationFile) {
            $output->writeln("<error>Migration file for $migrationName not found.</error>");
            return;
        }
        $classesBefore = get_declared_classes();
        require_once $migrationFile;
        $classesAfter = get_declared_classes();
        $newClasses = array_diff($classesAfter, $classesBefore);
        $migrationClass = null;
        foreach ($newClasses as $class) {
            if (method_exists($class, 'down')) {
                $migrationClass = $class;
                break;
            }
        }
        if (!$migrationClass) {
            $output->writeln("<error>No migration class with a down() method found in $migrationFile.</error>");
            return;
        }
        $migration = new $migrationClass();
        $migration->down();
        // Remove the migration record
        DB::rawQuery(MigrationQueries::deleteById($latest[0]->id), true);
        $output->writeln('<info>Rollback complete.</info>');
    }

    /**
     * Find all valid migration files in the migrations directory.
     * @return array List of valid migration file paths.
     */
    protected function findMigrationFiles()
    {
        $pattern = '/^\d{4}_\d{2}_\d{2}_\d{6}_.+\.php$/i';
        $files = glob($this->migrationsDir . '*.php');
        $valid = [];
        foreach ($files as $file) {
            if (preg_match($pattern, basename($file))) {
                $valid[] = $file;
            }
        }
        sort($valid);
        return $valid;
    }

    /**
     * Check if a migration has already been run.
     * @param string $migrationName
     * @return bool
     */
    protected function hasRun($migrationName)
    {
        $check = DB::rawQuery(MigrationQueries::selectByName($migrationName));
        if (!$check) $check = [];
        return count($check) > 0;
    }

    /**
     * Load the migration class from a file.
     * @param string $file
     * @return string|null Fully qualified class name or null if not found.
     */
    protected function loadMigrationClass($file)
    {
        $classesBefore = get_declared_classes();
        require_once $file;
        $classesAfter = get_declared_classes();
        $newClasses = array_diff($classesAfter, $classesBefore);
        foreach ($newClasses as $class) {
            if (method_exists($class, 'up')) {
                return $class;
            }
        }
        return null;
    }

    /**
     * Ensure the migrations table exists in the database.
     * @return void
     */
    protected function ensureMigrationsTable()
    {
        DB::rawQuery(MigrationQueries::CREATE_MIGRATIONS_TABLE, true);
    }
} 