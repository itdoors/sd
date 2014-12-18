<?php

namespace Lists\OrganizationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Lists\OrganizationBundle\Entity\Organization;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Classes\OrganizationAccessFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

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
                if ($rol && $rol->getLukey() == 'manager_organization' && $manager->getUser() == $user) {
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
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
      * 
      * @return Form $form
     */
    public function currentAccountFormDefaults(Form $form, $defaults)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $organizationId = (int) $defaults['organizationId'];
        $dorganization = $em->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);

        $form
            ->add('organizationId', 'hidden', array(
                'mapped' => false,
                'data' => $organizationId
            ))
            ->add('organization', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Organization',
                'empty_value' => '',
                'required' => true,
                'disabled' => true,
                'data' => $dorganization
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveCurrentAccountForm(Form $form, Request $request, $params)
    {
        $currentAccount = $form->getData();
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($currentAccount);
        $em->flush();
    }
}
