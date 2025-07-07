<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Core\Database\Doctrine;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migration:migrate')
            ->setDescription('Run all pending migrations.')
            ->setHelp('This command runs all pending migrations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = new Doctrine();
        // Ensure migrations table exists
        $doctrine->rawQuery("CREATE TABLE IF NOT EXISTS `migrations` (
          `id` int(11) NOT NULL AUTO_INCREMENT, primary key (id),
          `migration` varchar(255) NOT NULL,
          `is_migrate` varchar(255) NOT NULL
        );", true);
        $migrationsDir = 'migrations/';
        $files = glob($migrationsDir . '*.php');
        sort($files);
        $ran = 0;
        $selfName = basename(__FILE__);
        foreach ($files as $file) {
            $migrationName = basename($file);
            // No need for extra skip logic here; handled in saveMigration
            $check = $doctrine->rawQuery("SELECT * FROM migrations WHERE migration = '" . $migrationName . "'");
            if ($check && count($check) > 0) {
                $output->writeln("<info>Already migrated: $migrationName</info>");
                continue;
            }
            $output->writeln("<comment>Migrating: $migrationName</comment>");
            $classesBefore = get_declared_classes();
            require_once $file;
            $classesAfter = get_declared_classes();
            $newClasses = array_diff($classesAfter, $classesBefore);
            $migrationClass = null;
            foreach ($newClasses as $class) {
                if (method_exists($class, 'up')) {
                    $migrationClass = $class;
                    break;
                }
            }
            if (!$migrationClass) {
                $output->writeln("<error>No migration class with an up() method found in $migrationName.</error>");
                continue;
            }
            $migration = new $migrationClass();
            $migration->up();
            // Only save if a real migration was run (class with up() method)
            if ($migrationClass) {
                $doctrine->rawQuery("INSERT INTO migrations (migration, is_migrate) VALUES ('" . $migrationName . "','1')", true);
                $output->writeln("<info>Migrated: $migrationName</info>");
                $ran++;
            }
        }
        if ($ran === 0) {
            $output->writeln('<info>No new migrations to run.</info>');
        }
        return 0;
    }
}
