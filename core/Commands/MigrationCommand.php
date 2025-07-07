<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Core\Migrations\MigrationRunner;

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
        $runner = new MigrationRunner();
        $runner->runAll($output);
        return 0;
    }
}
