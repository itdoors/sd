<?php

/**
 * Command class for deleting handling
 */

namespace SD\ServiceDeskBundle\Command;

use Lists\IndividualBundle\Entity\Individual;
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

        $conn = $em->getConnection();

        $stmt = $conn->prepare('
          SELECT * FROM client
            LEFT JOIN sf_guard_user ON client.user_id = sf_guard_user.id

        ');
        $stmt->execute();

        $clients = $stmt->fetchAll();

       foreach($clients as $client) {
           $phone = $client['phone'];
           $mobilephone = $client['mobilephone'];

           $organization_id = $client['organization_id'];

           // userInfo

           $userFirstName = $client['first_name'];
           $userLastName = $client['last_name'];
           $userMiddleName = $client['middle_name'];
           $userPosition = $client['position'];
           $userEmail = $client['email_address'];
           $username = $client['username'];
           $userAlgorithm = $client['algorithm'];
           $userSalt = $client['salt'];
           $userPassword = $client['password'];
           $userIsActive = $client['is_active'];
           $userIsSuperAdmin = $client['is_super_admin'];
           $userLastLogin = $client['last_login'];
           $userSexID = $client['sex_id'];
           $userCreatedAt = $client['created_at'];

           $individual = new Individual();
           $individual->setFirstName($userFirstName);
           $individual->setLastName($userLastName);
           $individual->setMiddleName($userMiddleName);
           $setterPhone = $mobilephone;
           if (!$mobilephone) {
                $setterPhone = $phone;
           }
           $individual->setPhone($setterPhone);

           $user = 1;
           //$output->writeln($echo);
       }

    }
}
