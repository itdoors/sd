<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class SalesAdminController
 */
class OrganizationController extends BaseController
{
    protected $filterNamespace = 'organization.filters';
    protected $filterFormName = 'organizationSalesAdminFilterForm';
    protected $baseRoute = 'lists_sales_admin_organization_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * Renders organization list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferAction()
    {
        $namespase = $this->filterNamespace.'.transfer';

        return $this->render('ListsOrganizationBundle:Organization:organizationTransfer.html.twig', array(
                'namespase' => $namespase,
        ));
    }
    /**
     * Renders organization list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationForUserAction()
    {
        $namespase = $this->filterNamespace.'.transfer';
        $filters = $this->getFilters($namespase);

        $em = $this->getDoctrine()->getManager();
        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $em
            ->getRepository('ListsOrganizationBundle:Organization');

        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
            $organizationsQuery = array();
            $departmensQuery = array();
        } else {
            /** @var \Doctrine\ORM\Query */
            $organizationsQuery = $organizationsRepository->getAllForManagerQuery(null, $filters)->getResult();
            if ($this->getUser()->hasRole('ROLE_HRADMIN')) {
                $userId = $filters['user'];

                /** @var \SD\UserBundle\Entity\User $user */
                $user = $em->getRepository('SDUserBundle:User')->find($userId);

                $departmensQuery = $user->getStuff()->getStuffDepartments()->getDepartments();
            } else {
                $departmensQuery = array();
            }
            
        }

        return $this->render('ListsOrganizationBundle:Organization:organizationForUser.html.twig', array(
            'organizations' => $organizationsQuery,
            'departmens' => $departmensQuery
        ));
    }
    /**
     * Renders organization list
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferForUserAction(Request $request)
    {
        $namespase = $this->filterNamespace.'.transfer';
        $filters = $this->getFilters($namespase);

        $userIdOld = $filters['user'];
        $userId = $request->get('userId');
        $organizationIds = $request->get('organizations');

        if (empty($userIdOld) || empty($userId) || empty($organizationIds)) {
            return new Response(json_encode(array('error' => 'error data')));
        }
        $em = $this->getDoctrine()->getManager();
        /** @var \SD\UserBundle\Entity\User $u */
        $u = $this->getDoctrine()->getRepository('SDUserBundle:User')->find($userId);
        /** @var \Lists\OrganizationBundle\Entity\OrganizationUserRepository $oU */
        $oU = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationUser');

        $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
            ->findOneBy(array('lukey' => 'manager_organization'));

        foreach ($organizationIds as $organizationId) {
            $organizationUser = $oU->findOneBy(array(
                'organizationId' => $organizationId,
                'userId' => $userIdOld
                    ));
            if (!$organizationUser) {
                $organization = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);
                $organizationUser = new OrganizationUser();
                $organizationUser->setLookup($lookup);
                $organizationUser->setOrganization($organization);
            }
            $organizationUser->setUser($u);
            $em->persist($organizationUser);

            $serviceHandlingUser = $this->container->get('lists_handling.user.service');
            $serviceHandlingUser->changeManagerProject($organizationId, $userId);
        }
        $em->flush();

        $result = array('success' => true);

        return new Response(json_encode($result));
    }
}
