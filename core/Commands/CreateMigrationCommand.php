<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Core\Generators\Generator;

class CreateMigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the migration to generate.')
            ->setName('make:migration')
            ->setDescription('Creates a new migration file.')
            ->setHelp('This command allows you to create a new migration file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = Generator::getInstance();
        $output->writeln([
            '**************************************************',
        ]);

        $name = $input->getArgument('name');
        $result = $generator->generateMigrationFile($name);

        if ($result['status']) {
            $output->writeln(["<bg=green;options=bold>{$result['message']}</>"]);
        } else {
            $output->writeln(["<bg=red;options=bold>{$result['message']}</>"]);
        }

        return 0;
    }
} 