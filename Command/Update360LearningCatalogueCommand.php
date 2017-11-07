<?php

namespace Kassoulet\ThreesixtyLearningBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateThreesixtyLearningCatalogueCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this  ->setName('ThreesixtyLearning:updatecatalogue');
        //php bin/console 360Learning:updatecatalogue
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Récup du service ThreesixtyLearningService
        $ThreesixtyLearningService = $this->getApplication()->getKernel()->getContainer()->get('kassoulet.ThreesixtyLearning.utils');
        $ThreesixtyLearningService->updateStages();
        $output->writeln("ThreesixtyLearning : update catalogue ok");
    }
}

?>

