<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;

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
     * Renders organizationUsers list
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationTransferAction()
    {
        
        return $this->render('ListsOrganizationBundle:SalesAdmin:organizationTransfer.html.twig', array(

        ));
    }
}
