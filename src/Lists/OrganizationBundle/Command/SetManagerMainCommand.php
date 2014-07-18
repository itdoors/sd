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
          ->setName('lists:organization:set:manager:organization')
          ->setDescription('Set main manager for organization');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $organizations = $em->getRepository('ListsOrganizationBundle:Organization')->findAll();
        foreach ($organizations as $organization) {
            echo $organization->getId()." \t\n";
            $organizationUsers = $organization->getOrganizationUsers();
            $userId = false;
            if (count($organizationUsers) == 1) {
                $user = $organizationUsers[0];
                $userId = $user->getUser()->getId();
            } else if (count($organizationUsers) > 1) {
                $user = $organizationUsers[count($organizationUsers)-1];
                $userId = $user->getUser()->getId();
            } else if (count($organizationUsers) == 0 && method_exists($organization, 'getUser')) {
                $userId = $organization->getUser()->getId();
            } else if (!method_exists($organization, 'getUser')) {
                echo 'In organization not found user creator and manager id: '.$organization->getId()."\n";
            }
            if ($userId) {
                $serviceOrganizationUser = $this->getContainer()->get('lists_organization.user.service');
                $serviceOrganizationUser->changeManagerOrganizationProject($organization->getId(), $userId);
            }
        }
        $em->flush();
        $output->writeln('and');
    }
}