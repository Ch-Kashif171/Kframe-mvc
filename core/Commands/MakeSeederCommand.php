<?php

namespace Core\Commands;

use Core\Support\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeSeederCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:seeder')
            ->setDescription('Create a new database seeder class.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the seeder class');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = trim($input->getArgument('name'));
        $className = preg_replace('/[^A-Za-z0-9_]/', '', $className);
        $fileName = $className . '.php';
        $path = base_path() . '/database/seeders';

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filePath = root_path . '/'.Constants::SEEDER_DIR.'/' . $fileName;

        if (file_exists($filePath)) {
            $output->writeln("<error>Seeder '{$className}' already exists!</error>");
            return Command::FAILURE;
        }

        $stubPath = root_path . '/core/Templates/Seeders/SeederTemplate.php';

        if (!file_exists($stubPath)) {
            $output->writeln("<error>Stub file 'Seeders/SeederTemplates.php' not found.</error>");
            return Command::FAILURE;
        }

        $content = file_get_contents($stubPath);

        $className = ucfirst(basename(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $className)));
        $seederContent = str_replace('SeederTemplate', $className, $content);

        file_put_contents($filePath, $seederContent);

        $output->writeln("<info>Seeder '{$className}' created at: database/seeders/{$fileName}</info>");

        return Command::SUCCESS;
    }
}