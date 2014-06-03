<?php

namespace Lists\ReportBundle\Controller;

use Doctrine\ORM\Query;
use Lists\HandlingBundle\Entity\HandlingRepository;
use ITDoors\CommonBundle\Controller\BaseFilterController;

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

        $filterForm = $this->processFilters();

        $filters = $this->getFilters();

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
}
