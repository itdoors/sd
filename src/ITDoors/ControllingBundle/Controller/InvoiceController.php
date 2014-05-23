<?php

namespace ITDoors\ControllingBundle\Controller;

use Container;
use ITDoors\ControllingBundle\Entity\Invoice;
use Lists\DogovorBundle\Entity\Dogovor;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
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
    protected $paginator ='knp_paginator';
    /**
     *  indexAction
     * 
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

        $service= $this->container->get($this->service);
        $service->getTabsInvoices();

        $tabs = $service->getTabsInvoices();

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'tabs' => $tabs,
                'tab' => $period
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
        $sum = $result['sum'];

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator.'.count', $count);
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
                'sum' => $sum
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
        $service= $this->container->get($this->service);
        $service->getTabsInvoices();

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'invoiceshow' . $invoiceid;
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'act';
            $this->setTab($namespaceTab, $tab);
        }

        /** @var InvoiceRepository $invoiceObj*/
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
}
