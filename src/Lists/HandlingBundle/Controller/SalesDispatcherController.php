<?php

namespace Lists\HandlingBundle\Controller;

class SalesDispatcherController extends SalesController
{
    protected $filterNamespace = 'handling.sales.dispatcher.filters';
    protected $filterFormName = 'handlingSalesDispatcherFilterForm';
    protected $baseRoute = 'lists_sales_dispatcher_handling_index';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

    public function indexAction()
    {
        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        // Get organization filter
        $filters = $this->getFilters();

        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery($teamUserIds, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $handlingQuery,
            $page,
            20
        );

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate
        ));
    }
}

