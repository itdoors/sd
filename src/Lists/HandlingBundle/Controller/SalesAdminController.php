<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SalesAdminController extends SalesController
{
    protected $filterNamespace = 'handling.sales.admin.filters';
    protected $filterFormName = 'handlingSalesAdminFilterForm';
    protected $baseRoute = 'lists_sales_admin_handling_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

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

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $handlingQuery,
            $page,
            20
        );

        $canAddNew = $this->getFilterValueByKey('organization_id') ? true : false;

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':index.html.twig', array(
                'pagination' => $pagination,
                'filterForm' => $filterForm->createView(),
                'filterFormName' => $this->filterFormName,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate,
                'canAddNew' => $canAddNew
            ));
    }

    public function indexServicesAction()
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingServices = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

    }

    /**
     * Executes close action
     */
    public function closeAction($id)
    {
        if (!$id)
        {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_handling_index'));
        }

        $user = $this->getUser();

        if (!$user->hasRole('ROLE_SALESADMIN'))
        {
            throw new AccessDeniedException();
        }

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($id);

        $value = (Boolean) $handling->getIsClosed();

        $handling->setIsClosed(!$value);
        $handling->setClosedatetime(new \DateTime());
        $handling->setCloser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($handling);
        $em->flush();

        return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_handling_show', array(
                'id' => $id
            )));
    }
}

