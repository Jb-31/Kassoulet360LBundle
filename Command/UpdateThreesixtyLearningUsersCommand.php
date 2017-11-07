<?php

namespace Kassoulet\ThreesixtyLearningBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateThreesixtyLearningUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this  ->setName('ThreesixtyLearning:updateusers');
        //php bin/console ThreesixtyLearning:updateusers
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Récup du service ThreesixtyLearningService
        $ThreesixtyLearningService = $this->getApplication()->getKernel()->getContainer()->get('kassoulet.ThreesixtyLearning.utils');
        $ThreesixtyLearningService->updateUsers();               
        $output->writeln("ThreesixtyLearning : update users ok");
    }
}

?>

