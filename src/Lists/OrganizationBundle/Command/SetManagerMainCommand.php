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
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\OrganizationBundle\Entity\OrganizationUser;

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
                $organizationId = $organization->getId();

                /** @var Organization $organization */
                $organization = $em->getRepository('ListsHandlingBundle:Handling')->find($organizationId);

                /** @var User $user */
                $user = $em->getRepository('SDUserBundle:User')->find($userId);

                $managerOrganization = $em
                    ->getRepository('ListsOrganizationBundle:OrganizationUser')
                    ->findOneBy(array(
                        'organizationId' => $organizationId,
                        'lookupId' => $lookup->getId()
                        ));

                if (!$managerOrganization) {
                    $managerOrganization = $em
                        ->getRepository('ListsOrganizationBundle:OrganizationUser')
                        ->findOneBy(array(
                            'organizationId' => $organizationId,
                            'userId' => $userId
                        ));
                    if (!$managerOrganization) {
                        $managerOrganization = new OrganizationUser();
                        $managerOrganization->setUser($user);
                        $managerOrganization->setOrganization($organization);
                    }
                    $managerOrganization->setLookup($lookup);
                } else {
                    $oldManager = $em
                        ->getRepository('ListsOrganizationBundle:OrganizationUser')
                        ->findOneBy(array(
                            'organizationId' => $organizationId,
                            'userId' => $userId,
                            ));
                    if ($oldManager) {
                        $em->remove($oldManager);
                    }
                    $managerOrganization->setUser($user);
                }
                $em->persist($managerOrganization);

                /** @var Handling $handlings */
                $handlings = $em->getRepository('ListsHandlingBundle:Handling')
                        ->findBy(array('organization_id' => $organizationId));
                foreach ($handlings as $handling) {
                    $handlingId = $handling->getId();
                    $user = $em->getRepository('SDUserBundle:User')->find($userId);

                    $lookup = $em->getRepository('ListsLookupBundle:lookup')->findOneBy(array('lukey' => 'manager_project'));

                    $mainManager = $em
                        ->getRepository('ListsHandlingBundle:HandlingUser')
                        ->findOneBy(array(
                            'handlingId' => $handlingId,
                            'lookupId' => $lookup->getId(),
                            ));

                    if (!$mainManager) {
                        $mainManager = $em
                            ->getRepository('ListsHandlingBundle:HandlingUser')
                            ->findOneBy(array(
                                'handlingId' => $handlingId,
                                'userId' => $userId,
                            ));
                        if (!$mainManager) {
                            $mainManager = new HandlingUser();
                            $mainManager->setUser($user);
                            $mainManager->setHandling($handling);
                            $mainManager->setPart(100);
                            $mainManager->setLookup($lookup);
                        } else {
                            $mainManager->setLookup($lookup);
                            $mainManager->setPart(100);
                        }
                    } else {
                        $oldManager = $em
                            ->getRepository('ListsHandlingBundle:HandlingUser')
                            ->findOneBy(array(
                                'handlingId' => $handlingId,
                                'userId' => $userId,
                                ));
                        if ($oldManager) {
                            $part = $mainManager->getPart()+$oldManager->getPart();
                            $em->remove($oldManager);
                            if ($part > 100) {
                                $part = 100;
                            }
                            $mainManager->setPart($part);
                        } else {
                            if ($mainManager->getPart() === null) {
                                $mainManager->setPart(100);
                            }
                        }
                        $mainManager->setUser($user);
                    }
                    $em->persist($mainManager);
                }
            }
        }
        $em->flush();
        $output->writeln('and');
    }
}