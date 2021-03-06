<?php

namespace ITDoors\ControllingBundle\Controller;

use Container;
use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;
use ITDoors\ControllingBundle\Services\InvoiceService;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Doctrine\ORM\EntityManager;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use Symfony\Component\HttpFoundation\Request;
use Lists\ContactBundle\Entity\ModelContactSendEmail;
use ITDoors\ControllingBundle\Services\ControllingService;
use Lists\OrganizationBundle\Services\OrganizationService;

/**
 * InvoiceController
 */
class InvoiceController extends BaseFilterController
{

    /** @var Invoice $filterNamespace */
    protected $filterNamespace = 'ajax.filter.namespace.report.invoice';

    /** @var InvoiceService $service */
    protected $service = 'it_doors_invoice.service';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';
    protected $filterFormName = 'invoiceFilterForm';

    /**
     * @var Container
     *
     * @return Response
     */
    public function indexAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $filter = $this->filterFormName;

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 30;
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }
        $tabs = $service->getTabsInvoices($companystryctyre, $filters);

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array (
                'tabs' => $tabs,
                'filter' => $filter,
                'tab' => $period,
                'namespace' => $filterNamespace
        ));
    }
    /**
     * @var Container
     *
     * @return Response
     */
    public function showtabAction ()
    {

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $filter = $this->filterFormName;

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 30;
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);

        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }
        $tabs = $service->getTabsInvoices($companystryctyre, $filters);

        return $this->render('ITDoorsControllingBundle:Invoice:showtab.html.twig', array (
                'tabs' => $tabs,
                'filter' => $filter,
                'tab' => $period,
                'namespace' => $filterNamespace
        ));
    }
    /**
     *  showAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function showAction ()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }

        $period = $this->getTab($filterNamespace);

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        if ($period === 'all') {
            if (isset($filters['isFired'])) {
                $result = array();
            } else {
                $result = $invoice->getAll($filters, $companystryctyre);
            }

            return $this->render('ITDoorsControllingBundle:Invoice:all.html.twig', array (
                    'period' => $period,
                    'entities' => $result,
                    'filters' => $filters
            ));
        } else {
            $baseFilters = $this->get('it_doors_ajax.base_filter_service');
            $orders = $baseFilters->getOrdering($filterNamespace);

            $result = $invoice->getEntittyCountSum($period, $filters, $companystryctyre, $orders);
            $entities = $result['entities'];
            $count = $result['count'];

            $page = $this->getPaginator($filterNamespace);
            if (!$page) {
                $page = 1;
            }

            $paginator = $this->container->get($this->paginator);
            $entities->setHint($this->paginator . '.count', $count);
            $pagination = $paginator->paginate($entities, $page, 20);


            return $this->render('ITDoorsControllingBundle:Invoice:show.html.twig', array (
                    'period' => $period,
                    'entities' => $pagination,
                    'namespaceInvoice' => $filterNamespace,
                    'access' => $access
            ));
        }
    }
    /**
     *  invoice Action
     * 
     * @param integer $invoiceid id invoice 
     * 
     * @var Container
     * 
     * @return Response
     */
    public function invoiceAction ($invoiceid)
    {
        $session = $this->get('session');
        $session->set('invoiceid', $invoiceid);
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }
        /** @var InvoiceService $service */
        $service = $this->container->get($this->service);
        $service->getTabsInvoices($companystryctyre);

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'invoiceshow' . $invoiceid;
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'act';
            $this->setTab($namespaceTab, $tab);
        }

        /** @var InvoiceRepository $invoiceObj */
        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);

        $tabs = $service->getTabsInvoice();

        return $this->render('ITDoorsControllingBundle:Invoice:invoice.html.twig', array (
                'tabs' => $tabs,
                'tab' => $tab,
                'invoiceid' => $invoiceid,
                'invoice' => $invoiceObj,
                'namespaceTab' => $namespaceTab,
                'access' => $access
        ));
    }
    /**
     *  invoiceshowAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function invoiceshowAction ()
    {
        $session = $this->get('session');
        $invoiceid = $session->get('invoiceid');

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'invoiceshow' . $invoiceid;
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $entitie = $invoice->getInfoForTab($invoiceid, $tab);

        $hasCustomer = $invoice->find($invoiceid)->getCustomer() ? true : false;

        /** @var OrganizationService $serviceOrganization */
        $serviceOrganization = $this->get('lists_organization.service');
        $accessOrganization = $serviceOrganization->checkAccess($this->getUser());

        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());

        return $this->render('ITDoorsControllingBundle:Invoice:table' . $tab . '.html.twig', array (
                'namespaceTab' => $namespaceTab,
                'entitie' => $entitie,
                'hasCustomer' => $hasCustomer,
                'block' => $tab,
                'accessOrganization' => $accessOrganization,
                'access' => $access
        ));
    }
    /**
     *  lastactionAction
     * 
     * @return html Description
     */
    public function lastactionAction ()
    {
        $session = $this->get('session');
        $invoiceid = $session->get('invoiceid', false);

        /** @var InvoiceMessage $messages */
        $messages = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:InvoiceMessage')
            ->getInvoiceMessages($invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:lastaction.html.twig', array (
                'messages' => $messages,
                'invoiceid' => $invoiceid
        ));
    }
    /**
     *  listAction
     * 
     * @return render Description
     */
    public function listAction ()
    {
        $filterNamespace = $this->container->getParameter($this->filterNamespace) . 'list';
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Invoice $list */
        $list = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->getInvoiceListForDashboard();
        $count = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->getInvoiceListForDashboardCount();

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $list->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($list, $page, 10);

        $responsibles = array ();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array ('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:list.html.twig', array (
                'list' => $pagination,
                'responsibles' => $responsibles
        ));
    }
    /**
     *  expectedpayAction
     * 
     * @return render Description
     */
    public function expectedpayAction ()
    {
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $accessControlling = $serviceControlling->checkAccess($this->getUser());
        if (!$accessControlling->canSeeExpectedPay()) {
            throw new \Exception('No access', 403);
        }

        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expectedpay';
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'today';
            $this->setTab($namespaceTab, $tab);
        }
        $service = $this->container->get($this->service);
        $tabs = $service->getTabsExpectedPay();

        return $this->render('ITDoorsControllingBundle:Invoice:expectedpay.html.twig', array (
                'tabs' => $tabs,
                'tab' => $tab,
                'namespace' => $namespaceTab
        ));
    }
    /**
     *  expectedpayAction
     * 
     * @return render Description
     */
    public function expecteddataAction ()
    {
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $accessControlling = $serviceControlling->checkAccess($this->getUser());
        if (!$accessControlling->canSeeExpectedData()) {
            throw new \Exception('No access', 403);
        }

        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expecteddata';
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'delay';
            $this->setTab($namespaceTab, $tab);
        }
        $service = $this->container->get($this->service);
        $tabs = $service->getTabsEmptyData();

        return $this->render('ITDoorsControllingBundle:Invoice:expecteddata.html.twig', array (
                'tabs' => $tabs,
                'tab' => $tab,
                'namespace' => $namespaceTab
        ));
    }
    /**
     *  expectedpayshowAction
     * 
     * @return render Description
     */
    public function expectedpayshowAction ()
    {
        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expectedpay';
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }
        $result = $invoice->getEntittyCountSum($tab, null, $companystryctyre);
        $entities = $result['entities'];
        $count = $result['count'];

        $page = $this->getPaginator($namespaceTab);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);

        $responsibles = array ();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array ('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:expectedpayshow.html.twig', array (
                'period' => $tab,
                'entities' => $pagination,
                'responsibles' => $responsibles,
                'namespace' => $namespaceTab
        ));
    }
    /**
     *  expectedpayshowAction
     * 
     * @return render Description
     */
    public function expecteddatashowAction ()
    {
        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expecteddata';
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }
        $result = $invoice->getEntittyCountSum($tab, null, $companystryctyre);
        $entities = $result['entities'];
        $count = $result['count'];

        $page = $this->getPaginator($namespaceTab);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);

        $responsibles = array ();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array ('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:expecteddatashow.html.twig', array (
                'period' => $tab,
                'entities' => $pagination,
                'responsibles' => $responsibles,
                'namespace' => $namespaceTab
        ));
    }
    /**
     *  expectedpayshowAction
     * 
     * @param mixed $invoices
     * 
     * @return render Description
     */
    private function getExel ($invoices)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("DebtControll")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Invoice debitor")
            ->setSubject("Invoice debitor")
            ->setDescription("Invoice debitor")
            ->setKeywords("Invoice debitor")
            ->setCategory("Invoice debitor");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', $translator->trans('№', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('B1', $translator->trans('Date', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('C1', $translator->trans('Customer', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('C2', 'DebtControll')
            ->setCellValue('D2', '1C')
            ->setCellValue('E1', $translator->trans('Performer', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('F1', $translator->trans('Invoice amount', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('G1', $translator->trans('Debt', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('H1', $translator->trans('№ ABP/BH', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('I1', $translator->trans('Date ABP/BH', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('J1', $translator->trans('Original ABP/BN', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('K1', $translator->trans('Responsible', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('L1', $translator->trans('№ contract', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('L2', 'DebtControll')
            ->setCellValue('M2', '1C')
            ->setCellValue('N1', $translator->trans('Date contract', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('N2', 'DebtControll')
            ->setCellValue('O2', '1C')
            ->setCellValue('P1', $translator->trans('Deferment', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('Q1', $translator->trans('Notes', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('R1', $translator->trans('Expected date of Payment', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('S1', $translator->trans('Fact date of payment', array (), 'ITDoorsControllingBundle'))
            ->setCellValue('T1', $translator->trans('Status', array (), 'ITDoorsControllingBundle'));
        $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        $phpExcelObject->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
        $phpExcelObject->getActiveSheet()->mergeCells('A1:A2');
        $phpExcelObject->getActiveSheet()->mergeCells('B1:B2');
        $phpExcelObject->getActiveSheet()->mergeCells('C1:D1');
        $phpExcelObject->getActiveSheet()->mergeCells('E1:E2');
        $phpExcelObject->getActiveSheet()->mergeCells('F1:F2');
        $phpExcelObject->getActiveSheet()->mergeCells('G1:G2');
        $phpExcelObject->getActiveSheet()->mergeCells('H1:H2');
        $phpExcelObject->getActiveSheet()->mergeCells('I1:I2');
        $phpExcelObject->getActiveSheet()->mergeCells('J1:J2');
        $phpExcelObject->getActiveSheet()->mergeCells('K1:K2');
        $phpExcelObject->getActiveSheet()->mergeCells('L1:M1');
        $phpExcelObject->getActiveSheet()->mergeCells('N1:O1');
        $phpExcelObject->getActiveSheet()->mergeCells('P1:P2');
        $phpExcelObject->getActiveSheet()->mergeCells('Q1:Q2');
        $phpExcelObject->getActiveSheet()->mergeCells('R1:R2');
        $phpExcelObject->getActiveSheet()->mergeCells('S1:S2');
        $phpExcelObject->getActiveSheet()->mergeCells('T1:T2');
        $str = 2;

        foreach ($invoices as $invoice) {
            ++$str;
            $col = 0;

            if ($str % 2 == 0) {
                $phpExcelObject->getActiveSheet()->getStyle('A' . $str . ':P' . $str)->getFill()
                    ->applyFromArray(array ('type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array ('rgb' => 'f5f5f5')
                ));
            }
            $invoice['actOriginals'] = str_replace('t', 'есть', $invoice['actOriginals']);
            $invoice['actOriginals'] = str_replace('f', 'нет', $invoice['actOriginals']);
            $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow($col, $str, $invoice['invoiceId'])
                ->setCellValueByColumnAndRow(++$col, $str, !$invoice['date'] ? '' : $invoice['date']->format('d.m.Y'))
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['customerName'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['invoiceCustomerName'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['performerName'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['sum'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['debitSum'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['actNumbers'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['actDates'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['actOriginals'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['responsibles'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['dogovorNumber'])
                ->setCellValueByColumnAndRow(++$col, $str, $invoice['dogNumber'])
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['dogovorStartDatetime'] ? '' : $invoice['dogovorStartDatetime']->format('d.m.Y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['invoiceDogovorDate'] ? '' : $invoice['invoiceDogovorDate']->format('d.m.Y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['delayDate']
                    ?
                    ''
                    :
                    $invoice['delayDate']->format('d.m.Y') . ' (' . $invoice['delayDays'] . ')'
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['descriptiondate']
                    ?
                    ''
                    :
                    $invoice['descriptiondate']->format('d.m.Y') . ' (' . $invoice['description'] . ')'
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['dateEnd'] ? '' : $invoice['dateEnd']->format('d.m.Y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['dateFact'] ? '' : $invoice['dateFact']->format('d.m.Y')
                )
                ->setCellValueByColumnAndRow(
                    ++$col,
                    $str,
                    !$invoice['court'] ? '' : $invoice['court']
                );
        }
        $phpExcelObject->getActiveSheet()->getStyle('A2:P' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(13);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);

        $styleArray = array (
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                    'color' => array ('argb' => '000000'),
                ),
                'inside' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array ('argb' => '000000'),
                )
            ),
        );

        $phpExcelObject->getActiveSheet()->getStyle('A1:T' . $str)->applyFromArray($styleArray);

        $phpExcelObject->getActiveSheet()
            ->getStyle('A2:T' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:T2')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:T2')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $phpExcelObject->getActiveSheet()
            ->getStyle('C2:D' . $str)
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $phpExcelObject->getActiveSheet()
            ->getStyle('C2:D' . $str)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcelObject->getActiveSheet()->freezePane('AB3');

//        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getStyle('A1:T' . $str)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getStyle('A1:T2')->getFill()
            ->applyFromArray(array ('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array ('rgb' => 'eeeeee')
        ));
//        $phpExcelObject->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
//        $phpExcelObject->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->setShowGridLines(false); //off line
        $phpExcelObject->getActiveSheet()->setTitle('Invoice');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=debtControll.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
    /**
     *  expectedpayshowAction
     * 
     * 
     * @return render Description
     */
    public function exportExelAction ()
    {
        /** @var InvoiceRepository $invoices */
//        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')->getForExel($companystryctyre);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }

        $period = $this->getTab($filterNamespace);

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        if ($period === 'all') {
            if (isset($filters['isFired'])) {
                $invoices = array();
            } else {
                $result = $invoice->getEntittyCountSum($period.'Exel', $filters, $companystryctyre);
                $entities = $result['entities'];
                $invoices = $entities->getResult();
            }
        } else {
            $result = $invoice->getEntittyCountSum($period, $filters, $companystryctyre);
            $entities = $result['entities'];
            $invoices = $entities->getResult();
        }

        return $this->getExel($invoices);
    }
    /**
     *  expectedpayshowAction
     * 
     * 
     * @return render Description
     */
    public function exportExelDataAction ()
    {
        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expecteddata';
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }

        /** @var InvoiceRepository $invoices */
        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')
            ->getInvoiceEmptyData($tab, $companystryctyre)->getResult();

        return $this->getExel($invoices);
    }
    /**
     *  expectedpayshowAction
     * 
     * 
     * @return render Description
     */
    public function exportExelPayAction ()
    {
        $namespaceTab = $this->container->getParameter($this->getNamespace()).'expectedpay';
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        $companystryctyre = null;
        if (!$access->canSeeAll()) {
            $companystryctyre = $this->getCompanystructure();
        }

        /** @var InvoiceRepository $invoices */
        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')
            ->getInvoiceEmptyData($tab, $companystryctyre)->getResult();

        return $this->getExel($invoices);
    }
    /**
     *  sendEmailChangeAction
     * 
     * @param Request $request Description
     * 
     * @return render Description
     */
    public function sendEmailChangeAction (Request $request)
    {
        $idModelContact = $request->request->get('idModelContact');
        $idIsSend = $request->request->get('idIsSend');
        $isSend = $request->request->get('isSend');

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $sendEmail = false;
        if ($idIsSend) {
            /** @var ModelContactSendEmail $sendEmail */
            $sendEmail = $em->getRepository('ListsContactBundle:ModelContactSendEmail')->find($idIsSend);
        }
        if (!$sendEmail) {
            /** @var ModelContact $modelContact */
            $modelContact = $em->getRepository('ListsContactBundle:ModelContact')->find($idModelContact);
            $sendEmail = new ModelContactSendEmail();
            $sendEmail->setModelContact($modelContact);
        }
        $sendEmail->setIsSend($isSend);

        $em->persist($sendEmail);
        $em->flush();

        return new Response(json_encode(array ('id' => $sendEmail->getId())));
    }
    /**
     * getCompanystructure
     * 
     * @return Companystructure
     */
    private function getCompanystructure()
    {
        $companystryctyre = $this->getUser()->getStuff()->getCompanystructure();
        if ($companystryctyre->getLevel() > 1) {
            $repository = $this->getDoctrine()->getManager()
                ->getRepository('ListsCompanystructureBundle:companystructure');
            $companystryctyre = $repository->getParent($companystryctyre, 1);
        }

        return $companystryctyre;
    }
    /**
     * customersWithoutContactsAction
     * 
     * @return render
     */
    public function customersWithoutContactsAction  ()
    {
        /** @var ControllingService $serviceControlling */
        $serviceControlling = $this->get('it_doors_controlling.service');
        $access = $serviceControlling->checkAccess($this->getUser());
        if (!$access->canSeeCustomersWithoutContacts()) {
            throw new \Exception('No access', 403);
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $companystryctyre = $this->getCompanystructure();
        /** @var InvoiceRepository $invoice */
        $customer = $em->getRepository('ListsOrganizationBundle:Organization')
            ->getWithoutContactsForInvoice($companystryctyre->getId());

        return $this->render('ITDoorsControllingBundle:Invoice:customersWithoutContacts.html.twig', array (
            'customer' => $customer
        ));
    }
}
