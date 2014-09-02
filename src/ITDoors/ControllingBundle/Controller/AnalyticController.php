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
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . 'Grafic';

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 'general';
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);

        $tabs = $service->getTabsInvoiceGrafics();

        return $this->render('ITDoorsControllingBundle:Analytic:index.html.twig', array (
                'tabs' => $tabs,
                'tab' => $period,
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
    public function graficGeneralAction ()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $entities = $organization->getForInvoice();


        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array (
                'entities' => $entities
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $entities = $organization->getForInvoiceAct();

        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array (
                'entities' => $entities
        ));
    }
    /**
     *  greficGeneralAction
     * 
     * @param boolean $ajax
     * 
     * @return Response
     */
    public function graficIndividualAction ($ajax)
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . 'GraficIndividual';

        $filter = $this->filterFormName;

        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividual.html.twig', array (
                'filter' => $filter,
                'ajax' => $ajax,
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
    public function graficIndividualListsAction ()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()) . 'GraficIndividual';

        $filters = $this->getFilters($filterNamespace);
        $showType = null;
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }
        if (!empty($filters['dateRange'])) {
            $dates = explode('-', $filters['dateRange']);
            $dateFrom = new \DateTime($dates[0]);
            $dateTo = new \DateTime($dates[1]);
            if ($dateFrom->format('mY') == $dateTo->format('mY')) {
                $showType = array (
                    'year' => $dateFrom->format('Y'),
                    'month' => $dateFrom->format('m')
                );
            }
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        /** @var InvoicePaymentsRepository $invoicePay */
        $invoicePay = $em->getRepository('ITDoorsControllingBundle:InvoicePayments');

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $result = $organization->getForInvoiceAndCount($filters);
        $count = $result['count'];
        $entities = $result['entity'];

        $namespasePagin = $filterNamespace;
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);

        $invoices = array ();
        foreach ($pagination as $organization) {
            $invoices[$organization['id']] = array ();
            $invoiceAs = $invoice->getForCustomer($organization['id'], $filters);
            $invoicePs = $invoicePay->getForCustomer($organization['id'], $filters);
            $yearsA = array ();
            $yearsP = array ();
            $yearsPR = array ();
            $years = array ();
            foreach ($invoiceAs as $invoiceA) {
                $y = $invoiceA['date']->format('Y');
                $m = $invoiceA['date']->format('m');
                $d = $invoiceA['date']->format('j');

                $yd = $invoiceA['delayDate']->format('Y');
                $md = $invoiceA['delayDate']->format('m');
                $dd = $invoiceA['delayDate']->format('j');
                if (!array_key_exists($y, $years)) {
                    $years[$y] = array ();
                }
                if (!array_key_exists($yd, $years)) {
                    $years[$yd] = array ();
                }
                if (!array_key_exists($y, $yearsA)) {
                    $yearsA[$y] = array ();
                }
                if (!array_key_exists($m, $yearsA[$y])) {
                    $yearsA[$y][$m]
                        = array ('summaInMonth' => 0);
                }
                if (!array_key_exists($d, $yearsA[$y][$m])) {
                    $yearsA[$y][$m][$d] = 0;
                }
                $yearsA[$y][$m]['summaInMonth'] = $yearsA[$y][$m]['summaInMonth'] +
                    ($invoiceA['sum'] -
                    $invoiceA['paymentsSumma']
                    );
                $yearsA[$y][$m][$d] = $yearsA[$y][$m][$d] +
                    ($invoiceA['sum'] -
                    $invoiceA['paymentsSumma']
                    );

                if (!array_key_exists($yd, $yearsPR)) {
                    $yearsPR[$yd] = array ();
                }
                if (!array_key_exists($md, $yearsPR[$yd])) {
                    $yearsPR[$yd][$md] = array ('summaInMonth' => 0);
                }
                if (!array_key_exists($dd, $yearsPR[$yd][$md])) {
                    $yearsPR[$yd][$md][$dd] = 0;
                }
                $yearsPR[$yd][$md]['summaInMonth']
                    = $yearsPR[$yd][$md]['summaInMonth']
                    + ($invoiceA['sum'] - $invoiceA['paymentsSumma']);
                $yearsPR[$yd][$md][$dd]
                    = $yearsPR[$yd][$md][$dd]
                    + ($invoiceA['sum'] - $invoiceA['paymentsSumma']);
            }
            foreach ($invoicePs as $invoiceP) {
                $y = $invoiceP['date']->format('Y');
                $m = $invoiceP['date']->format('m');
                $d = $invoiceP['date']->format('j');
                if (!array_key_exists($y, $years)) {
                    $years[$y] = array ();
                }
                if (!array_key_exists($y, $yearsP)) {
                    $yearsP[$y] = array ();
                }
                if (!array_key_exists($m, $yearsP[$y])) {
                    $yearsP[$y][$m]
                        = array ('summaInMonth' => 0);
                }
                if (!array_key_exists($d, $yearsP[$y][$m])) {
                    $yearsP[$y][$m][$d] = 0;
                }
                $yearsP[$y][$m]['summaInMonth']
                    = $yearsP[$y][$m]['summaInMonth']
                    + $invoiceP['summaPay'];
                $yearsP[$y][$m][$d]
                    = $yearsP[$y][$m][$d] +
                    $invoiceP['summaPay'];
            }
            $invoices[$organization['id']]['invoice'] = $invoiceAs;
            $invoices[$organization['id']]['invoicePays'] = $invoicePs;
            $invoices[$organization['id']]['years'] = $years;
            $invoices[$organization['id']]['invoicesA'] = $yearsA;
            $invoices[$organization['id']]['invoicesP'] = $yearsP;
            $invoices[$organization['id']]['invoicesPR'] = $yearsPR;
        }

        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividualLists.html.twig', array (
                'showType' => $showType,
                'entities' => $pagination,
                'invoices' => $invoices,
                'namespace' => $filterNamespace,
                'namespasePagin' => $namespasePagin
        ));
    }
}
