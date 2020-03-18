<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Core\Generators\Generator;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('migrate', InputArgument::REQUIRED, 'Name Of The migrate to Generate.')
            ->addArgument('option', InputArgument::OPTIONAL, 'Want to Generate a Migration?')
            ->setName('migration')
            ->setDescription('Creates new Migration.')
            ->setHelp("This command allows you to create new Migration...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = Generator::getInstance();
        $output->writeln([
            /*"<comment>Executing Command -> make:controller {$input->getArgument('controllername')}</comment>",*/
            '**************************************************',
        ]);

            $build = $generator->generateMigration($input->getArgument('migrate'));
            $output->writeln(["<bg=green;options=bold>{$build['message']}</>"]);

            if ($build['status']) {
                $output->writeln(["<bg=green;options=bold>{$build['message']}</>"]);
            }
            else {
                $output->writeln(["<bg=red;options=bold>{$build['message']}</>"]);
            }

            return 0;
    }
}
