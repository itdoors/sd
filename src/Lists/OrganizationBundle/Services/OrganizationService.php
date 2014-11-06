<?php

namespace Lists\OrganizationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Lists\OrganizationBundle\Entity\Organization;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Classes\OrganizationAccessFactory;

/**
 * OrganizationService class
 */
class OrganizationService
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
     * checkAccess
     * 
     * @param User         $user
     * @param Organization $organization
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, Organization $organization = null)
    {
        $role = array();
        $role[] = 'base';
        if ($organization) {
            $managers = $organization->getOrganizationUsers();
            foreach ($managers as $manager) {
                $rol = $manager->getRole();
                if ($rol->getLukey() == 'manager_organization' && $manager->getUser() == $user) {
                    $role[] = 'managerOrganization';
                }
            }
        }
        if ($user->hasRole('ROLE_CONTROLLING_OPER')) {
            $role[] = 'controlling_oper';
        }
        if ($user->hasRole('ROLE_CONTROLLING')) {
            $role[] = 'controlling';
        }
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'sales';
        }
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $role[] = 'sales_admin';
        }
        if ($user->hasRole('ROLE_DOGOVORADMIN')) {
            $role[] = 'dogovor_admin';
        }

        return OrganizationAccessFactory::createAccess($role);
    }
}
