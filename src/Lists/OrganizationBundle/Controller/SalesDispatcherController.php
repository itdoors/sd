<?php

namespace Lists\OrganizationBundle\Controller;

use Lists\OrganizationBundle\Controller\SalesController as SalesController;

class SalesDispatcherController extends SalesController
{
    protected $filterNamespace = 'organization.sales.dispatcher.filters';
    protected $filterForm = 'organizationSalesDispatcherFilterForm';
    protected $baseRoute = 'lists_sales_dispatcher_organization_index';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \SD\UserBundle\Entity\User $user*/
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery(null, $this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:Sales:index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterForm,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

}

