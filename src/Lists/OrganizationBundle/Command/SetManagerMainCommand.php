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

        /** @var Lookup $lookup */
        $lookup = $em->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager_organization'));
        /** @var Lookup $lookupMp */
        $lookupMp = $em->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager_project'));
        /** @var Lookup $lookupM */
        $lookupM = $em->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager'));

        $organizations = $em->getRepository('ListsOrganizationBundle:Organization')->findAll();
        foreach ($organizations as $organization) {
            echo $organization->getId()." \t\n";
            $organizationUsers = $organization->getOrganizationUsers();
            $organizationUser = false;
            if (count($organizationUsers) == 1) {
                $organizationUser = $organizationUsers[0];
                if (!$organizationUser->getLookup()) {
                    $organizationUser->setLookup($lookup);
                    $em->persist($organizationUser);
                }
            } else if (count($organizationUsers) > 1) {
                $organizationUser = $organizationUsers[count($organizationUsers)-1];
                if (!$organizationUser->getLookup()) {
                    $organizationUser->setLookup($lookup);
                    $em->persist($organizationUser);
                }
            } else if (count($organizationUsers) == 0 && method_exists($organization, 'getUser')) {
                $organizationUser = new \Lists\OrganizationBundle\Entity\OrganizationUser();
                $organizationUser->setLookup($lookup);
                $organizationUser->setUser($organization->getUser());
                $organizationUser->setOrganization($organization);
                $em->persist($organizationUser);
            } else if (!method_exists($organizationUser, 'getUser')) {
                echo 'In organization not found user creator and manager id: '.$organization->getId()."\n";
            }
            if ($organizationUser) {
                $handlings = $em->getRepository('ListsHandlingBundle:Handling')
                        ->findBy(array(
                            'organization_id' => $organizationUser->getOrganizationId()
                        ));
                foreach ($handlings as $handling) {
                    $handlingUsers = $handling->getHandlingUsers();
                    foreach ($handlingUsers as $handlingUser) {
                        if ($handlingUser->getUserId() == $organizationUser->getUserId() && !$handlingUser->getPart()) {
                            $handlingUser->setPart(100);
                            $em->persist($handlingUser);
                        }
                        if ($handlingUser->getUserId() == $organizationUser->getUserId() && !$handlingUser->getLookup()) {
                            $handlingUser->setLookup($lookupMp);
                            $em->persist($handlingUser);
                        }
                        if ($handlingUser->getUserId() != $organizationUser->getUserId() && !$handlingUser->getLookup()) {
                            $handlingUser->setLookup($lookupM);
                            $em->persist($handlingUser);
                        }
                    }
                }
            }
        }
        $em->flush();
        $output->writeln('and');
    }
}