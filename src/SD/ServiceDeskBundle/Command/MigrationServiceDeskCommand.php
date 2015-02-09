<?php

/**
 * Command class for deleting handling
 */

namespace SD\ServiceDeskBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

/**
 * Class MigrationServiceDeskCommand
 */
class MigrationServiceDeskCommand extends ContainerAwareCommand
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
    protected function configure ()
    {
        $this
            ->setName('sd:migration:service-desk')
            ->setDescription('Db data migration to new structure');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $query = $em->createQuery(
            'SELECT phone
            FROM client
            '
        );//->setParameter('price', '19.99');

        $clients = $query->getResult();

       foreach($clients as $client) {
           $echo = $client['phone'];
           $output->writeln($echo);
       }

    }
}
