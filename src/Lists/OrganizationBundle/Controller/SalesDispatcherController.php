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
        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($teamUserIds, $this->getFilters());

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
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
