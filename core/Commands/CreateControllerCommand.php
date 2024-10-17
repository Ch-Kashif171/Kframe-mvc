<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Core\Generators\Generator;

class CreateControllerCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('controllername', InputArgument::REQUIRED, 'Name Of The Controller to Generate.')
            ->addArgument('option', InputArgument::OPTIONAL, 'Want to Generate a Model?')
            ->setName('make:Controllers')
            ->setDescription('Creates new Controller.')
            ->setHelp("This command allows you to create new Controller...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $generator = Generator::getInstance();
        $output->writeln([
            /*"<comment>Executing Command -> make:Controllers {$input->getArgument('controllername')}</comment>",*/
            '**************************************************',
        ]);

        $getArgumentOption = $input->getArgument('option');

        if (!empty($getArgumentOption) && strtolower($getArgumentOption) == 'withmodel') {
            $generator->generateModel($input->getArgument('controllername'));
            $build = $generator->generateController($input->getArgument('controllername'));
        }
        else{
            $build = $generator->generateController($input->getArgument('controllername'));
        }

        if ($build['status']) {
            $output->writeln(["<bg=green;options=bold>{$build['message']}</>"]);
        }
        else {
            $output->writeln(["<bg=red;options=bold>{$build['message']}</>"]);
        }

        return 0;

    }
}
