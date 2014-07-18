<?php

/**
 * Command class for deleting handling
 */
namespace Lists\OrganizationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

/**
 * Class DeleteCommand
 */
class SetManagerMainCommand extends ContainerAwareCommand
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
          ->setName('lists:handling:set:manager:main')
          ->setDescription('Set main manager for handling');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $lookupId = $em
            ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerProjectId();
        $lookupIdM = $em
            ->getRepository('ListsLookupBundle:Lookup')->getManagerProjectId();

        $lookup = $em
            ->getRepository('ListsLookupBundle:Lookup')
            ->find($lookupId);
        $lookupm = $em
            ->getRepository('ListsLookupBundle:Lookup')
            ->find($lookupIdM);
        
        $handlings = $em->getRepository('ListsHandlingBundle:Handling')->findAll();
        foreach ($handlings as $handling) {
            $userId = $handling->getUser()->getId();
            $handlingUsers = $handling->getHandlingUsers();
            foreach ($handlingUsers as $handlingUser) {
                if($handlingUser->getUserId() == $userId && !$handlingUser->getPart()) {
                    $handlingUser->setPart(100);
                    $em->persist($handlingUser);
                }
                if($handlingUser->getUserId() == $userId && !$handlingUser->getLookup()) {
                    $handlingUser->setLookup($lookup);
                    $em->persist($handlingUser);
                }
                if($handlingUser->getUserId() != $userId && !$handlingUser->getLookup()) {
                    $handlingUser->setLookup($lookupm);
                    $em->persist($handlingUser);
                }
            }
        }
        $em->flush();
        $output->writeln('and');
    }

}
