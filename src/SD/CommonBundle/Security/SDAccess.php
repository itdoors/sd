<?php

/**
 * Class for checking access to entities
 */

namespace SD\CommonBundle\Security;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SD\UserBundle\Entity\User;

/**
 * SDAccess
 */
class SDAccess
{
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
     * If users in organization user
     *
     * @param int $organizationId
     *
     * @return boolean
     */
    public function hasAccessToOrganization($organizationId)
    {
        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // FOR SALES ADMIN
        if ($user->hasRole('ROLE_SALESADMIN')) {
            return true;
        }

        // FOR DOGOVOR ADMIN
        if ($user->hasRole('ROLE_DOGOVORADMIN')) {
            return true;
        }

        $userIds = array();

        //FOR SALES AND SALES DISPATCHER

        if ($user->hasRole('ROLE_SALESDISPATCHER')) {
            /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
            $teamRepository = $this->get('lists_team.repository');

            $userIds = $teamRepository->getMyTeamIdsByUser($user);
        } else {
            if ($user->hasRole('ROLE_SALES')) {
                $userIds = array($user->getId());
            }
        }

        if (!sizeof($userIds)) {
            return false;
        }

        $organizationUsers = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('ListsOrganizationBundle:Organization')
            ->createQueryBuilder('o')
            ->leftJoin('o.users', 'users')
            ->where('o.id  = :organizationId')
            ->andWhere('users.id in (:usersId)')
            ->setParameter(':organizationId', $organizationId)
            ->setParameter(':usersId', $userIds)
            ->getQuery()
            ->getResult();

        if (sizeof($organizationUsers)) {
            return true;
        }

        return false;
    }

    /**
     * If users in organization user
     *
     * @param int $organizationId
     *
     * @throws AccessDeniedException
     *
     * @return boolean
     */
    public function hasAccessToOrganizationAndThrowException($organizationId)
    {
        if (!$this->hasAccessToOrganization($organizationId)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * If users in handling user
     *
     * @param int $handlingId
     *
     * @return boolean
     */
    public function hasAccessToHandling($handlingId)
    {
        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Form SaLES ADMIN
        if ($user->hasRole('ROLE_SALESADMIN')) {
            return true;
        }

        $userIds = array();

        //FOR SALES AND SALES DISPATCHER

        if ($user->hasRole('ROLE_SALESDISPATCHER')) {
            /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
            $teamRepository = $this->get('lists_team.repository');

            $userIds = $teamRepository->getMyTeamIdsByUser($user);
        } else {
            if ($user->hasRole('ROLE_SALES')) {
                $userIds = array($user->getId());
            }
        }

        if (!sizeof($userIds)) {
            return false;
        }

        $handlingUsers = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('ListsHandlingBundle:Handling')
            ->createQueryBuilder('h')
            ->leftJoin('h.users', 'users')
            ->where('h.id  = :handlingId')
            ->andWhere('users.id in (:usersId)')
            ->setParameter(':handlingId', $handlingId)
            ->setParameter(':usersId', $userIds)
            ->getQuery()
            ->getResult();

        if (sizeof($handlingUsers)) {
            return true;
        }

        return false;
    }

    /**
     * If users in organization user
     *
     * @param int $handlingId
     *
     * @throws AccessDeniedException
     *
     * @return boolean
     */
    public function hasAccessToHandlingAndThrowException($handlingId)
    {
        if (!$this->hasAccessToHandling($handlingId)) {
            throw new AccessDeniedException();
        }
    }
}
