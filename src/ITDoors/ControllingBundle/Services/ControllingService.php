<?php

namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use ITDoors\ControllingBundle\Classes\ControllingAccessFactory;
use SD\UserBundle\Entity\User;

/**
 * Controlling Service class
 */
class ControllingService
{

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }
    /**
     * checkAccess
     * 
     * @param User         $user
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user)
    {
        $role = array();
        $role[] = 'base';
        if ($user->hasRole('ROLE_CONTROLLING_OPER')) {
            $role[] = 'controlling_oper';
        }
        if ($user->hasRole('ROLE_CONTROLLING')) {
            $role[] = 'controlling';
        }
        if ($user->hasRole('ROLE_CONTROLLING_VIEWER')) {
            $role[] = 'controlling_viewer';
        }

        return ControllingAccessFactory::createAccess($role);
    }
}
