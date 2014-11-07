<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Classes\HandlingAccessFactory;
use SD\UserBundle\Entity\User;

/**
 * HandlingService class
 */
class HandlingService
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
     * @param User     $user
     * @param Handling $handling
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, Handling $handling = null)
    {
        $role = array();
        $role[] = 'base';
        if ($handling) {
            $handlingUsers = $handling->getHandlingUsers();
            foreach ($handlingUsers as $handlingUser) {
                $rol = $handlingUser->getLookup();
                if ($rol && $rol->getLukey() == 'manager_project' && $handlingUser->getUser() == $user) {
                    $role[] = 'manager_project';
                }
                if ($rol && $rol->getLukey() == 'manager' && $handlingUser->getUser() == $user) {
                    $role[] = 'manager';
                }
            }
        }
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $role[] = 'sales_admin';
        }
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'sales';
        }

        return HandlingAccessFactory::createAccess($role);
    }
}
