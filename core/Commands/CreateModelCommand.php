<?php
namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Core\Generators\Generator;

class CreateModelCommand extends Command
{
  protected function configure()
  {
    $this
    ->addArgument('modelname', InputArgument::REQUIRED, 'Name Of The Controller to Generate.')
    ->addArgument('option', InputArgument::OPTIONAL, 'Enter withController if you Want to Generate a Controller')
    ->setName('make:model')
    ->setDescription('Creates new Model.')
    ->setHelp("This command allows you to create new Model...");
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    // outputs multiple lines to the console (adding "\n" at the end of each line)
    $generator = Generator::getInstance();
    $output->writeln([
      /*"<comment>Executing Command -> make:model {$input->getArgument('modelname')}</comment>",*/
      '**************************************************',
    ]);

    $getArgumentOption = $input->getArgument('option');
    if (strtolower($getArgumentOption) == 'withcontroller') {
      $generator->generateController($input->getArgument('modelname'));
      $build = $generator->generateModel($input->getArgument('modelname'));
    }
    else{
      $build = $generator->generateModel($input->getArgument('modelname'));
    }

    if ($build['status']) {
      $output->writeln(["<bg=green;options=bold>{$build['message']}</>"]);
    }
    else {
      $output->writeln(["<bg=red;options=bold>{$build['message']}</>"]);
    }

  }
}
