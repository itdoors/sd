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
    public function indexAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()).'Grafic';

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 'general';
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);

        $tabs = $service->getTabsInvoiceGrafics();

        return $this->render('ITDoorsControllingBundle:Analytic:index.html.twig', array(
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
    public function graficGeneralAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $entities = $organization->getForInvoice();


        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array(
//                'period' => $period,
                'entities' => $entities,
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
    public function graficWithoutactsAction()
    {
          /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');

        $entities = $organization->getForInvoiceAct();

        return $this->render('ITDoorsControllingBundle:Analytic:graficGeneral.html.twig', array(
//                'period' => $period,
                'entities' => $entities,
//                'namespasePagin' => $namespasePagin
        ));
    }
    /**
     *  greficGeneralAction
     * 
     * @var Container
     * 
     * @param boolean $ajax
     * 
     * @return Response
     */
    public function graficIndividualAction($ajax)
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()).'GraficIndividual';

        $filter = $this->filterFormName;
        
        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividual.html.twig', array(
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
    public function graficIndividualListsAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace()).'GraficIndividual';

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        /** @var InvoicePaymentsRepository $invoicePay */
        $invoicePay = $em->getRepository('ITDoorsControllingBundle:InvoicePayments');

//        $result = $invoice->getForAnalytic($filters);
//        $entities = $result['entities'];
//        $count = $result['count'];
        
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
        
        $invoices = array();
        foreach ($pagination as $organization) {
            $invoices[$organization['id']] = array();
            $invoiceAs = $invoice->getForCustomer($organization['id'], $filters);
            $invoicePs = $invoicePay->getForCustomer($organization['id'], $filters);
            $yearsA = array();
            $yearsP = array();
            $yearsPR = array();
            $years = array();
            foreach ($invoiceAs as $invoiceA) {
                if(!array_key_exists($invoiceA['date']->format('Y'), $years)) {
                    $years[$invoiceA['date']->format('Y')] = array();
                }
                if(!array_key_exists($invoiceA['delayDate']->format('Y'), $years)) {
                    $years[$invoiceA['delayDate']->format('Y')] = array();
                }
                
                if(!array_key_exists($invoiceA['date']->format('Y'), $yearsA)) {
                    $yearsA[$invoiceA['date']->format('Y')] = array();
                }
                if(!array_key_exists($invoiceA['date']->format('m'), $yearsA[$invoiceA['date']->format('Y')])) {
                    $yearsA[$invoiceA['date']->format('Y')][$invoiceA['date']->format('m')] = 0;
                }
                $yearsA[$invoiceA['date']->format('Y')][$invoiceA['date']->format('m')]
                        = 
                        $yearsA[$invoiceA['date']->format('Y')][$invoiceA['date']->format('m')]
                        +
                        ($invoiceA['sum']
                        -
                        $invoiceA['paymentsSumma']
                        );
                
                if(!array_key_exists($invoiceA['delayDate']->format('Y'), $yearsPR)) {
                    $yearsPR[$invoiceA['delayDate']->format('Y')] = array();
                }
                if(!array_key_exists($invoiceA['delayDate']->format('m'), $yearsPR[$invoiceA['delayDate']->format('Y')])) {
                    $yearsPR[$invoiceA['delayDate']->format('Y')][$invoiceA['delayDate']->format('m')] = 0;
                }
                $yearsPR[$invoiceA['delayDate']->format('Y')][$invoiceA['delayDate']->format('m')]
                        = 
                        $yearsPR[$invoiceA['delayDate']->format('Y')][$invoiceA['delayDate']->format('m')]
                        +
                        ($invoiceA['sum']
                        -
                        $invoiceA['paymentsSumma']
                        );
            }
            foreach ($invoicePs as $invoiceP) {
                if(!array_key_exists($invoiceP['date']->format('Y'), $years)) {
                    $years[$invoiceP['date']->format('Y')] = array();
                }
                if(!array_key_exists($invoiceP['date']->format('Y'), $yearsP)) {
                    $yearsP[$invoiceP['date']->format('Y')] = array();
                }
                if(!array_key_exists($invoiceP['date']->format('m'), $yearsP[$invoiceP['date']->format('Y')])) {
                    $yearsP[$invoiceP['date']->format('Y')][$invoiceP['date']->format('m')] = 0;
                }
                $yearsP[$invoiceP['date']->format('Y')][$invoiceP['date']->format('m')]
                        = 
                        $yearsP[$invoiceP['date']->format('Y')][$invoiceP['date']->format('m')]
                        +
                        $invoiceP['summaPay'];
            }
            $invoices[$organization['id']]['invoice'] = $invoiceAs;
            $invoices[$organization['id']]['invoicePays'] = $invoicePs;
            $invoices[$organization['id']]['years'] = $years;
            $invoices[$organization['id']]['invoicesA'] = $yearsA;
            $invoices[$organization['id']]['invoicesP'] = $yearsP;
            $invoices[$organization['id']]['invoicesPR'] = $yearsPR;
        }
        
        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividualLists.html.twig', array(
                'entities' => $pagination,
                'invoices' => $invoices,
                'namespace' => $filterNamespace,
                'namespasePagin' => $namespasePagin
        ));
    }
//    /**
//     *  greficGeneralAction
//     * 
//     * @var Container
//     * 
//     * @return Response
//     */
//    public function graficIndividualListsAction()
//    {
//        $filterNamespace = $this->container->getParameter($this->getNamespace()).'GraficIndividual';
//
//        $filters = $this->getFilters($filterNamespace);
//        if (empty($filters)) {
//            $filters['isFired'] = 'No fired';
//            $this->setFilters($filterNamespace, $filters);
//        }
//        /** @var EntityManager $em */
//        $em = $this->getDoctrine()->getManager();
//
//        /** @var InvoiceRepository $invoice */
//        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
//
//        $result = $invoice->getForAnalytic($filters);
//        $entities = $result['entities'];
//        $count = $result['count'];
//        
//        $namespasePagin = $filterNamespace;
//        $page = $this->getPaginator($namespasePagin);
//        if (!$page) {
//            $page = 1;
//        }
//
//        $paginator = $this->container->get($this->paginator);
//        $entities->setHint($this->paginator . '.count', $count);
//        $pagination = $paginator->paginate($entities, $page, 100);
//        
//        return $this->render('ITDoorsControllingBundle:Analytic:graficIndividualLists.html.twig', array(
//                'entities' => $pagination,
//                'namespace' => $filterNamespace,
//                'namespasePagin' => $namespasePagin
//        ));
//    }
}
