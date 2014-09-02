<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesDispatcherController
{
    protected $filterNamespace = 'organization.sales.admin.filters';
    protected $filterFormName = 'organizationSalesAdminFilterForm';
    protected $baseRoute = 'lists_sales_admin_organization_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $namespase = $this->filterNamespace;
        $filter = $this->filterFormName;

        return $this->render('ListsOrganizationBundle:Sales:index.html.twig', array(
            'namespase' => $namespase,
            'filter' => $filter,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery(null, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Sales:list.html.twig', array(
            'pagination' => $pagination,
            'namespase' => $namespase,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * exportExcelAction
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

       /** @var \Doctrine\ORM\Query */
        $organizations = $organizationsRepository->getAllForSalesQuery(null, $filters)->getResult();

        $response = $this->exportToExcelAction($organizations);

        return $response;
    }
    /**
     * Renders organization list
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferAction(Request $request)
    {
        $namespase = $this->filterNamespace.'ForUser';

        return $this->render('ListsOrganizationBundle:SalesAdmin:organizationTransfer.html.twig', array(
                'namespase' => $namespase,
        ));
    }
    /**
     * Renders organization list
     * 
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationForUserAction(Request $request)
    {
        $namespase = $this->filterNamespace.'ForUser';
        $filters = $this->getFilters($namespase);

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
            $organizationsQuery = array();
        } else {
            /** @var \Doctrine\ORM\Query */
            $organizationsQuery = $organizationsRepository->getAllForManagerQuery(null, $filters)->getResult();
        }

        return $this->render('ListsOrganizationBundle:SalesAdmin:organizationForUser.html.twig', array(
            'pagination' => $organizationsQuery
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
        $namespase = $this->filterNamespace.'ForUser';
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
                'userId' => $userIdOld,
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
