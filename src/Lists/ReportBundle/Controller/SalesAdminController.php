<?php

namespace Lists\ReportBundle\Controller;

use Doctrine\ORM\Query;
use Lists\ContactBundle\Services\ModelContactService;
use Lists\HandlingBundle\Entity\HandlingMessageRepository;
use Lists\HandlingBundle\Entity\HandlingMessageTypeRepository;
use Lists\HandlingBundle\Entity\HandlingRepository;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\HandlingBundle\Entity\HandlingService;
use Lists\HandlingBundle\Services\HandlingMessageService;

/**
 * SalesAdminController
 */
class SalesAdminController extends BaseFilterController
{
    protected $filterNamespace = 'report.sales.admin.filters';
    protected $filterFormName = 'reportSalesAdminFilterForm';
    protected $baseRoute = 'lists_report_sales_admin_handling_status';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * Handling status report
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportHandlingStatusAction()
    {
        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->get('handling.repository');

        $filterForm = $this->createForm($this->filterFormName);

        $filters['progressNOT'] = 100;
        $filters['isClosed'] = 'FALSE';

        /** @var Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery(null, $filters);

        $results = $handlingQuery->getResult();

        return $this->render('ListsReportBundle:' . $this->baseTemplate . ':reportHandlingStatus.html.twig', array(
            'results' => $results,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
            'filterForm' => $filterForm->createView()
        ));
    }

    /**
     * Manager last messages report
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportLastMessagesAction()
    {
        return $this->render('ListsReportBundle:' . $this->baseTemplate . ':reportLastMessages.html.twig', array(
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
        ));
    }

    /**
     * Manager last messages report table
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportLastMessagesTableAction()
    {
        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->get('handling.repository');

        $filterNamespace = $this->container->getParameter('ajax.filter.namespace.report.last.messages');

        $filters = $this->getFilters($filterNamespace);

        $results = $handlingRepository->getReportLastMessages($filters);

        return $this->render('ListsReportBundle:' . $this->baseTemplate . ':reportLastMessagesTable.html.twig', array(
            'results' => $results,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
        ));
    }

    /**
     * reportActivityAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportActivityAction()
    {
        /** @var HandlingService[] $services */
        $services = $this->getDoctrine()->getManager()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        return $this->render('ListsReportBundle:' . $this->baseTemplate . ':reportActivity.html.twig', array(
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
            'services' => $services
        ));
    }

    /**
     * ReportActivityContentAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportActivityContentAction()
    {
        $filterNamespace = $this->container->getParameter('ajax.filter.namespace.report.activity');

        $filters = $this->getFilters($filterNamespace);

        /** @var HandlingMessageRepository $hmr */
        $hmr = $this->get('handling.message.repository');
        /** @var HandlingMessageService $hms */
        $hms = $this->get('handling.message.service');
        /** @var HandlingMessageTypeRepository $hmtr */
        $hmtr = $this->get('handling.message.type.repository');

        /** @var HandlingMessageRepository $handlingMessageRepository */
        $results = $hmr->getActivity($filters, $hms->getReportSlugs());

        $types = $hmtr->getListBySlug($hms->getReportSlugs());

        /** @var ModelContactService $mcs */
        $mcs = $this->get('lists_contact.contact.service');

        $levels = $mcs->getLevels();

        return $this->render('ListsReportBundle:' . $this->baseTemplate . ':reportActivityContent.html.twig', array(
            'results' => $results,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
            'types' => $types,
            'levels' => $levels
        ));
    }
}
