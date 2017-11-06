<?php

namespace Kassoulet\360LBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update360LearningUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this  ->setName('360Learning:updateusers');
        //php bin/console 360Learning:updateusers
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	//Récup du service 360LService
    	$360LService = $this->getApplication()->getKernel()->getContainer()->get('kassoulet.360Learning.utils');
    	$360LService->updateUsers();               
        $output->writeln("360Learning : update users ok");
    }
}

?>

