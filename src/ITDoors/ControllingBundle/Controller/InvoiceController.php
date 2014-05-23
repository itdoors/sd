<?php

namespace ITDoors\ControllingBundle\Controller;

use ITDoors\ControllingBundle\Entity\Invoice;
use Lists\DogovorBundle\Entity\Dogovor;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;
use ITDoors\ControllingBundle\Services\ParserService;

/**
 * InvoiceController
 */
class InvoiceController extends BaseFilterController
{
     /**
     * @var filterNamespace $filterNamespace
     */
    protected $filterNamespace = 'ajax.filter.namespace.report.invoice';

    /**
     * @var Parser $parser
     */
    protected $parser = 'it_doors_parser.service';
    /**
     *  indexAction
     * 
     * @return html Description
     */
    public function indexAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $period = $this->getTab($filterNamespace);
        if (!$period) {
            $period = 30;
            $this->setTab($filterNamespace, $period);
        }
        $tabs = $this->getTabsInvoices();

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'tabs' => $tabs,
                'tab' => $period
        ));
    }

    /**
     *  showAction
     * 
     * @return html Description
     */
    public function showAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $period = $this->getTab($filterNamespace);

        $em = $this->getDoctrine()->getManager();
        /** @var ITDoors/ControllingBundle/Entity/InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');


        $result = $invoice->getEntittyCountSum($period);
        $entities = $result['entities'];
        $count = $result['count'];
        $sum = $result['sum'];

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $entities->setHint('knp_paginator.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);

        $responsibles = array();
        foreach ($pagination as $val) {
            /** @var ITDoors/ControllingBundle/Entity/InvoiceCompanystructure */
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
     *  invoiceAction
     * 
     * @param int       $invoiceid
     * 
     * @return html Description
     */
    public function invoiceAction($invoiceid)
    {
        $session = $this->get('session');
        $session->set('invoiceid', $invoiceid);

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'invoiceshow' . $invoiceid;
        $tab = $this->getTab($namespaceTab);
        if (!$tab) {
            $tab = 'act';
            $this->setTab($namespaceTab, $tab);
        }

        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);

        $tabs = $this->getTabsInvoice();

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
     * @return html Description
     */
    public function invoiceshowAction()
    {

        $session = $this->get('session');
        $invoiceid = $session->get('invoiceid');

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $namespaceTab = $filterNamespace . 'invoiceshow' . $invoiceid;
        $tab = $this->getTab($namespaceTab);

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
        if (!$invoiceid) {
            throw $this->createNotFoundException('Param "invoiceid" not found in session.');
        }
        $messages = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:InvoiceMessage')
            ->getInvoiceMessages((int) $invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:lastaction.html.twig', array(
                'messages' => $messages,
                'invoiceid' => $invoiceid
        ));
    }
    
    /**
     *  sendmessageAction

     * @return 
     */
    public function sendmessageAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Привет из symfony')
            ->setFrom('send@mail.ru')
            ->setTo('senj@mail.ru')
            ->setBody('текст письма');
        $this->get('mailer')->send($message);

        return new Response('send message!');
    }

    /**
     *  cronAction
     * 
     * @return html Description
     */
    public function cronAction()
    {
        /** @var ParserService $parser*/
        $parser= $this->container->get($this->parser);
        
        // directory
        $directory = '../app/share/1c/debt/';

        $parser->findFile($directory);

        return new Response('<html><body>Cron work!</body></html>');
    }
    /**
     * Returns results for interval future invoice
     *
     * @return tabs[]
     */
    private function getTabsInvoices()
    {
        $translator = $this->get('translator');
        $tabs[] = array(
            'tab' => 30,
            'blockupdate' => 'ajax-tab-holder',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('from') . ' 1 ' . $translator->trans('to') . ' 30 ' . $translator->trans('day')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 60,
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 31 ' . $translator->trans('to') . ' 60 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 120,
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 61 ' . $translator->trans('to') . ' 120 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 180,
            'class' => '',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 121 ' . $translator->trans('to') . ' 180 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 181,
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 181 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'court',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('court')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'pay',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('pay')
        );

        return $tabs;
    }

    /**
     * Returns results for interval future invoice
     *
     * @return tabs[]
     */
    private function getTabsInvoice()
    {
        $translator = $this->get('translator');
        $tabs = array();
        $tabs['act'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'act',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Act')
        );
        $tabs['invoice'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'invoice',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Invoice')
        );
        $tabs['contacts'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'contacts',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Contacts client')
        );
        $tabs['dogovor'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'dogovor',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Dogovor')
        );
        $tabs['responsible'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'responsible',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Responsible')
        );
        $tabs['organization'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'customer',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Customer')
        );
        $tabs['history'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'history',
            'url' => $this->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('History')
        );
        return $tabs;
    }
}
