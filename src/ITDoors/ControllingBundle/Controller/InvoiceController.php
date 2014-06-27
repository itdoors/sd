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

    /**
     * @var Container
     *
     * @return Response
     */
    public function indexAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 30;
            $this->setTab($filterNamespace, $period);
        }

        $service = $this->container->get($this->service);

        $tabs = $service->getTabsInvoices();

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'tabs' => $tabs,
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
    public function showAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $period = $this->getTab($filterNamespace);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $result = $invoice->getEntittyCountSum($period);
        $entities = $result['entities'];
        $count = $result['count'];

        $namespasePagin = $filterNamespace.'P'.$period;
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);

        $responsibles = array();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:show.html.twig', array(
                'period' => $period,
                'entities' => $pagination,
                'responsibles' => $responsibles,
                'namespasePagin' => $namespasePagin
        ));
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
    public function invoiceAction($invoiceid)
    {
        $session = $this->get('session');
        $session->set('invoiceid', $invoiceid);

        /** @var InvoiceService $service */
        $service = $this->container->get($this->service);
        $service->getTabsInvoices();

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

        return $this->render('ITDoorsControllingBundle:Invoice:invoice.html.twig', array(
                'tabs' => $tabs,
                'tab' => $tab,
                'invoiceid' => $invoiceid,
                'invoice' => $invoiceObj,
                'namespaceTab' => $namespaceTab
        ));
    }

    /**
     *  invoiceshowAction
     * 
     * @var Container
     * 
     * @return Response
     */
    public function invoiceshowAction()
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

        return $this->render('ITDoorsControllingBundle:Invoice:table' . $tab . '.html.twig', array(
                'namespaceTab' => $namespaceTab,
                'entitie' => $entitie,
                'block' => $tab
        ));
    }

    /**
     *  lastactionAction
     * 
     * @return html Description
     */
    public function lastactionAction()
    {
        $session = $this->get('session');
        $invoiceid = $session->get('invoiceid', false);

        /** @var InvoiceMessage $messages */
        $messages = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:InvoiceMessage')
            ->getInvoiceMessages((int) $invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:lastaction.html.twig', array(
                'messages' => $messages,
                'invoiceid' => $invoiceid
        ));
    }

    /**
     *  listAction
     * 
     * @return render Description
     */
    public function listAction()
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

        $responsibles = array();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:list.html.twig', array(
                'list' => $pagination,
                'responsibles' => $responsibles
        ));
    }

    /**
     *  expectedpayAction
     * 
     * @return render Description
     */
    public function expectedpayAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'expectedpay';
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'today';
            $this->setTab($namespaceTab, $tab);
        }
        $service = $this->container->get($this->service);
        $tabs = $service->getTabsExpectedPay();

        return $this->render('ITDoorsControllingBundle:Invoice:expectedpay.html.twig', array(
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
    public function expectedpayshowAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'expectedpay';
        $tab = $this->getTab($namespaceTab);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $result = $invoice->getEntittyCountSum($tab);
        $entities = $result['entities'];
        $count = $result['count'];

        $page = $this->getPaginator($namespaceTab);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);

        $responsibles = array();
        foreach ($pagination as $val) {
            /** @var InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:expectedpayshow.html.twig', array(
                'period' => $tab,
                'entities' => $pagination,
                'responsibles' => $responsibles,
                'namespace' => $namespaceTab
        ));

    }
}
