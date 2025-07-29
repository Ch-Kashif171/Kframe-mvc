<?php

namespace Core\Commands;

use Database\Seeders\DatabaseSeeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSeedCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:seed')
            ->setDescription('Seed the database using DatabaseSeeder class.');
    }

    protected function execute($input, OutputInterface $output): int
    {

        try {

            $seeder = new DatabaseSeeder();

            $seeder->run();

            $output->writeln('<info>Database seeding completed successfully.</info>');
        } catch (\Throwable $e) {
            $output->writeln('<error>Error while seeding: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}