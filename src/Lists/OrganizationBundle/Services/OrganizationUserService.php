<?php

namespace Lists\OrganizationBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Lists\LookupBundle\Entity\Lookup;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Entity\Organization;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use ITDoors\HistoryBundle\Entity\History;

/**
 * OrganizationUserService class
 */
class OrganizationUserService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Save form
     *
     * @param integer $organizationId
     * @param integer $userId
     */
    public function changeManagerOrganizationProject($organizationId, $userId)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var Organization $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);

        /** @var Lookup $lookup */
        $lookup = $em->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager_organization'));
        /** @var User $user */
        $user = $em->getRepository('SDUserBundle:User')->find($userId);

        $managerOrganization = $em
            ->getRepository('ListsOrganizationBundle:OrganizationUser')
            ->findOneBy(array(
                'organizationId' => $organizationId,
                'roleId' => $lookup->getId()
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
            $managerOrganization->setRole($lookup);
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
            /** @var User $authUser */
            $authUser = $this->container->get('security.context')->getToken()->getUser();

            $history = new History();
            $history->setCreatedatetime(new \DateTime());
            $history->setFieldName('user_id');
            $history->setModelName('organization_user');
            $history->setModelId($managerOrganization->getId());
            $history->setUser($authUser);
            $history->setOldValue($managerOrganization->getUserId());
            $history->setValue($user->getId());
            $em->persist($history);

            $managerOrganization->setUser($user);
        }
        $em->persist($managerOrganization);

        $serviceHandlingUser = $this->container->get('lists_handling.user.service');
        $serviceHandlingUser->changeManagerProject($organizationId, $userId);

        $em->flush();
    }
}
