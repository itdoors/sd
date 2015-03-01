<?php

/**
 * Command class for deleting handling
 */
namespace Lists\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Lists\ProjectBundle\Entity\ServiceProjectStateTender;
use Lists\ProjectBundle\Entity\StatusProjectStateTender;

/**
 * Class MigrationCommand
 */
class MigrationCommand extends ContainerAwareCommand
{
    /**
    * @var \Doctrine\ORM\EntityManager $em
    */
    protected $em;

    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
          ->setName('lists:project:migration')
          ->setDescription('Mifration handling to project');
    }
     /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START');
        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
         // перенос проектов
        $handlings = $this->em->getRepository('ListsHandlingBundle:Handling')->findAll();
        foreach ($handlings as $handling) {
            $this->updateData($handling, $output);
        }
        $this->em->flush();
        $output->writeln('END');
    }
    
    private function updateData($handling, $output){
        $project = $handling->getProject();
        if (!$project) {
             $output->writeln('project not found for id: '.$handling->getId());
        } else {
            $output->writeln('need to write the implementation )))');
        }
        
    }
}
