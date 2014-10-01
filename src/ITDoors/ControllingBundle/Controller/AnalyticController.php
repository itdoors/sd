<?php

namespace ITDoors\ControllingBundle\Controller;

use Container;
use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;
use ITDoors\ControllingBundle\Services\InvoiceService;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Doctrine\ORM\EntityManager;

/**
 * AnalyticController
 */
class AnalyticController extends BaseFilterController
{

    /** @var Invoice $filterNamespace */
    protected $filterNamespace = 'ajax.filter.namespace.report.invoice';

    /** @var InvoiceService $service */
    protected $service = 'it_doors_invoice.service';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';
    protected $filterFormName = 'invoiceFilterForAnalyticForm';

    /**
     * @var Container
     *
     * @return Response
     */
    public function indexAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . '.Analytic';

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 'individual';
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);

        $tabs = $service->getTabsInvoiceGrafics();

        $filter = $this->filterFormName;

        return $this->render('ITDoorsControllingBundle:Analytic:index.html.twig', array (
                'tabs' => $tabs,
                'tab' => $period,
                'namespace' => $filterNamespace,
                'filter' => $filter
        ));
    }
     /**
     *  greficGeneralAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function listAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . '.Analytic';

        $filters = $this->getFilters($filterNamespace);
        $showType = null;
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }
        $tab = $this->getTab($filterNamespace);
        if (!$tab) {
            $tab = 'individual';
            $this->setTab($filterNamespace, $tab);
        }
        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        $service = $this->container->get($this->service);

        $method = 'get'.ucfirst($tab);
        $result = $service->$method($page, $filters);

        return $this->render('ITDoorsControllingBundle:Analytic:'.$tab.'.html.twig', array (
                'showType' => $showType,
                'entities' => $result['entities'],
                'paginator' => $result['paginator'],
                'showDays' => $result['showDays'],
                'namespase' => $filterNamespace
        ));
    }
    /**
     *  greficGeneralAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function graficIndividualListsAction ()
    {
//        $filterNamespace = $this->container->getParameter($this->getNamespace()) . '.grafic';
//
//        $filters = $this->getFilters($filterNamespace);
//        $showType = null;
//        if (empty($filters)) {
//            $filters['isFired'] = 'No fired';
//            $this->setFilters($filterNamespace, $filters);
//        }
//        if (!empty($filters['dateRange'])) {
//            $dates = explode('-', $filters['dateRange']);
//            $dateFrom = new \DateTime($dates[0]);
//            $dateTo = new \DateTime($dates[1]);
//            if ($dateFrom->format('mY') == $dateTo->format('mY')) {
//                $showType = array (
//                    'year' => $dateFrom->format('Y'),
//                    'month' => $dateFrom->format('m')
//                );
//            }
//        }
//        /** @var EntityManager $em */
//        $em = $this->getDoctrine()->getManager();
//
//        /** @var InvoiceRepository $invoice */
//        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
//        /** @var InvoicePaymentsRepository $invoicePay */
//        $invoicePay = $em->getRepository('ITDoorsControllingBundle:InvoicePayments');
//
//        /** @var OrganizationRepository $organization */
//        $organization = $em->getRepository('ListsOrganizationBundle:Organization');
//
//        $result = $organization->getForInvoiceAndCount($filters);
//        $count = $result['count'];
//        $entities = $result['entity'];
//
//        $namespasePagin = $filterNamespace;
//        $page = $this->getPaginator($namespasePagin);
//        if (!$page) {
//            $page = 1;
//        }
//
//        $paginator = $this->container->get($this->paginator);
//        $entities->setHint($this->paginator . '.count', $count);
//        $pagination = $paginator->paginate($entities, $page, 20);
//
//        $invoices = array ();
//        foreach ($pagination as $organization) {
//            $invoices[$organization['id']] = array ();
//            $invoiceAs = $invoice->getForCustomer($organization['id'], $filters);
//            $invoicePs = $invoicePay->getForCustomer($organization['id'], $filters);
//            $yearsA = array ();
//            $yearsP = array ();
//            $yearsPR = array ();
//            $years = array ();
//            foreach ($invoiceAs as $invoiceA) {
//                $y = $invoiceA['date']->format('Y');
//                $m = $invoiceA['date']->format('m');
//                $d = $invoiceA['date']->format('j');
//
//                $yd = $invoiceA['delayDate']->format('Y');
//                $md = $invoiceA['delayDate']->format('m');
//                $dd = $invoiceA['delayDate']->format('j');
//                if (!array_key_exists($y, $years)) {
//                    $years[$y] = array ();
//                }
//                if (!array_key_exists($yd, $years)) {
//                    $years[$yd] = array ();
//                }
//                if (!array_key_exists($y, $yearsA)) {
//                    $yearsA[$y] = array ();
//                }
//                if (!array_key_exists($m, $yearsA[$y])) {
//                    $yearsA[$y][$m]
//                        = array ('summaInMonth' => 0);
//                }
//                if (!array_key_exists($d, $yearsA[$y][$m])) {
//                    $yearsA[$y][$m][$d] = 0;
//                }
//                $yearsA[$y][$m]['summaInMonth'] = $yearsA[$y][$m]['summaInMonth'] +
//                    ($invoiceA['sum'] -
//                    $invoiceA['paymentsSumma']
//                    );
//                $yearsA[$y][$m][$d] = $yearsA[$y][$m][$d] +
//                    ($invoiceA['sum'] -
//                    $invoiceA['paymentsSumma']
//                    );
//
//                if (!array_key_exists($yd, $yearsPR)) {
//                    $yearsPR[$yd] = array ();
//                }
//                if (!array_key_exists($md, $yearsPR[$yd])) {
//                    $yearsPR[$yd][$md] = array ('summaInMonth' => 0);
//                }
//                if (!array_key_exists($dd, $yearsPR[$yd][$md])) {
//                    $yearsPR[$yd][$md][$dd] = 0;
//                }
//                $yearsPR[$yd][$md]['summaInMonth']
//                    = $yearsPR[$yd][$md]['summaInMonth']
//                    + ($invoiceA['sum'] - $invoiceA['paymentsSumma']);
//                $yearsPR[$yd][$md][$dd]
//                    = $yearsPR[$yd][$md][$dd]
//                    + ($invoiceA['sum'] - $invoiceA['paymentsSumma']);
//            }
//            foreach ($invoicePs as $invoiceP) {
//                $y = $invoiceP['date']->format('Y');
//                $m = $invoiceP['date']->format('m');
//                $d = $invoiceP['date']->format('j');
//                if (!array_key_exists($y, $years)) {
//                    $years[$y] = array ();
//                }
//                if (!array_key_exists($y, $yearsP)) {
//                    $yearsP[$y] = array ();
//                }
//                if (!array_key_exists($m, $yearsP[$y])) {
//                    $yearsP[$y][$m]
//                        = array ('summaInMonth' => 0);
//                }
//                if (!array_key_exists($d, $yearsP[$y][$m])) {
//                    $yearsP[$y][$m][$d] = 0;
//                }
//                $yearsP[$y][$m]['summaInMonth']
//                    = $yearsP[$y][$m]['summaInMonth']
//                    + $invoiceP['summaPay'];
//                $yearsP[$y][$m][$d]
//                    = $yearsP[$y][$m][$d] +
//                    $invoiceP['summaPay'];
//            }
//            $invoices[$organization['id']]['invoice'] = $invoiceAs;
//            $invoices[$organization['id']]['invoicePays'] = $invoicePs;
//            $invoices[$organization['id']]['years'] = $years;
//            $invoices[$organization['id']]['invoicesA'] = $yearsA;
//            $invoices[$organization['id']]['invoicesP'] = $yearsP;
//            $invoices[$organization['id']]['invoicesPR'] = $yearsPR;
//        }

        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividualLists.html.twig', array (
//                'showType' => $showType,
//                'entities' => $pagination,
//                'invoices' => $invoices,
//                'namespace' => $filterNamespace,
//                'namespasePagin' => $namespasePagin
        ));
    }
    /**
     *  greficGeneralAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function graficGeneralAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . 'GraficGeneral';
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $result = $organization->getForInvoice();
        $entities = $result['entity'];
        $count = $result['count'];

        $namespasePagin = $filterNamespace;
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 100);

        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array (
                'entities' => $pagination,
                'namespace' => $filterNamespace
        ));
    }
    /**
     *  greficGeneralAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function graficWithoutactsAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . 'GraficWithoutacts';

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $result = $organization->getForInvoiceAct();
        $entities = $result['entity'];
        $count = $result['count'];

        $namespasePagin = $filterNamespace;
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 65);

        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array (
                'entities' => $pagination,
                'namespace' => $filterNamespace
        ));
    }
}
