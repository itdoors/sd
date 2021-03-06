<?php

namespace SD\DashboardBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use SD\UserBundle\Entity\User;
use SD\DashboardBundle\Classes\DashboardAccessFactory;

/**
 * DashboardService class
 */
class DashboardService
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
     * @param User $user
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user)
    {
        $role = array();
        $role[] = 'Basic';
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'Sales';
        }
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $role[] = 'SalesAdmin';
        }

        return DashboardAccessFactory::createAccess($role);
    }
}
