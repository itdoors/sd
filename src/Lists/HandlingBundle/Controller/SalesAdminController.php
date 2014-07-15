<?php

namespace Lists\HandlingBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesController
{
    protected $filterNamespace = 'handling.sales.admin.filters';
    protected $filterFormName = 'handlingSalesAdminFilterForm';
    protected $baseRoute = 'lists_sales_admin_handling_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * indexServicesAction
     */
    public function indexServicesAction()
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingServices = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

    }

    /**
     * Executes close action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function closeAction($id)
    {
        if (!$id) {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_handling_index'));
        }

        $user = $this->getUser();

        if (!$user->hasRole('ROLE_SALESADMIN')) {
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportSimpleAction()
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, array());

        $results = $handlingQuery->getResult();

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':reportSimple.html.twig', array(
                'results' => $results,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportAdvancedRangeAction()
    {
        $form = $this->createForm('handlingReportDateRangeForm');

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':reportAdvancedRange.html.twig', array(
                'form' => $form->createView(),
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate
            ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reportAdvancedDoneAction(Request $request)
    {
        $form = $this->createForm('handlingReportDateRangeForm');

        $data = $request->request->get($form->getName());

        if (!sizeof($data)) {
            return $this->redirect($this->generateUrl('lists_sales_admin_report_advanced_range'));
        }

        $from = new \DateTime($data['from']);
        $to = new \DateTime($data['to']);

        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $handlingRepository */
        $handlingMessageRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        /** @var HandlingMessageRepository $handlingMessageRepository */
        $results = $handlingMessageRepository->getAdvancedResult($from, $to);

        $types = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingMessageType')
            ->getList();

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':reportAdvancedDone.html.twig', array(
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate,
                'results' => $results,
                'types' => $types
            ));

    }

    /**
     * Executes list action for dashboard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        // Get organization filter
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $filters['progressNOT'] = 100;
        $filters['chanceNOT'] = array(0, 100);
        $filters['isClosed'] = 'FALSE';

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, $filters);

        $pagination = $handlingQuery->getResult();

        /** @var \Knp\Component\Pager\Paginator $paginator */

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':list.html.twig', array(
                'pagination' => $pagination,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate,
            ));
    }
     /**
     * Renders organizationUsers list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportExcelAction()
    {
         // Get organization filter
        $filters = $this->getFilters();

        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForExport(null, $filters);

        $response = $this->exportToExcelAction($handlingQuery);

        return $response;
    }
}
