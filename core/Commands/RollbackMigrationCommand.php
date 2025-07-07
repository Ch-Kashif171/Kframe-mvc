<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Core\Database\Doctrine;

class RollbackMigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migration:rollback')
            ->setDescription('Rollback the last run migration.')
            ->setHelp('This command rolls back the last run migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = new Doctrine();
        $latest = $doctrine->rawQuery('SELECT * FROM migrations ORDER BY id DESC LIMIT 1');
        if (!$latest) $latest = [];
        if (count($latest) === 0) {
            $output->writeln('<error>No migrations to rollback.</error>');
            return 0;
        }
        $migrationName = $latest[0]->migration;
        $output->writeln(["Rolling back migration: $migrationName"]);
        // Try to find the migration file
        $migrationFile = null;
        foreach (glob('migrations/*.php') as $file) {
            if (basename($file) === $migrationName || basename($file, '.php') === $migrationName) {
                $migrationFile = $file;
                break;
            }
        }
        if (!$migrationFile) {
            $output->writeln("<error>Migration file for $migrationName not found.</error>");
            return 1;
        }
        require_once $migrationFile;
        // Try to find the class in the file
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
            return 1;
        }
        $migration = new $migrationClass();
        $migration->down();
        // Remove the migration record
        $doctrine->rawQuery('DELETE FROM migrations WHERE id = ' . (int)$latest[0]->id, true);
        $output->writeln('<info>Rollback complete.</info>');
        return 0;
    }
} 