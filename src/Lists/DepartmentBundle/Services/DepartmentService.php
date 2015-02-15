<?php

namespace Lists\DepartmentBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Lists\DepartmentBundle\Classes\DepartmentAccessFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SD\UserBundle\Entity\User;

/**
 * DepartmentService class
 */
class DepartmentService
{
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
        $role[] = 'base';
        if ($user->hasRole('ROLE_HRADMIN')) {
            $role[] = 'hradmin';
        }

        return DepartmentAccessFactory::createAccess($role);
    }
}
