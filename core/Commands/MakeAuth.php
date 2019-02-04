<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Core\Generators\Generator;


class MakeAuth extends Command
{

    protected function configure()
    {
        $this
            ->addArgument('auth', InputArgument::REQUIRED, 'Name Of The Controller to Generate.')
            ->addArgument('option', InputArgument::OPTIONAL, 'Enter withController if you Want to Generate a Controller')
            ->setName('make:auth')
            ->setDescription('Creates auth scaffolding.')
            ->setHelp("This command allows you to create auth scaffolding...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $generator = Generator::getInstance();
        $output->writeln([
            /*"<comment>Executing Command -> make:auth {$input->getArgument('auth')}</comment>",*/
            '**************************************************',
        ]);

        $getArgumentOption = $input->getArgument('option');

        $build = $generator->generateAuth($input->getArgument('auth'));

        if ($build['status']) {
            $output->writeln(["<bg=green;options=bold>{$build['message']}</>"]);
        }
        else {
            $output->writeln(["<bg=red;options=bold>{$build['message']}</>"]);
        }

    }

}