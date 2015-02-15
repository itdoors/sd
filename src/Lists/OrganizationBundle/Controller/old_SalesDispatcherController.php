<?php

namespace Lists\OrganizationBundle\Controller;

use Lists\OrganizationBundle\Controller\SalesController as SalesController;

/**
 * Class SalesDispatcherController
 */
class SalesDispatcherController extends SalesController
{
    protected $filterNamespace = 'organization.sales.dispatcher.filters';
    protected $filterFormName = 'organizationSalesDispatcherFilterForm';
    protected $baseRoute = 'lists_sales_dispatcher_organization_index';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

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
        $namespase = $this->filterFormName;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

         /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($teamUserIds, $filters);

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
}
