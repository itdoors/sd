<?php

namespace Lists\ReportBundle\Controller;

use Doctrine\ORM\Query;
use Lists\HandlingBundle\Entity\HandlingRepository;
use SD\CommonBundle\Controller\BaseFilterController;

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
     */
    public function reportHandlingStatusAction()
    {
        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->get('handling.repository');

        $filterForm = $this->processFilters();

        $filters = $this->getFilters();

        $filters['progress'] = 100;
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
}
