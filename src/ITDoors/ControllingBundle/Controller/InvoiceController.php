<?php

namespace ITDoors\ControllingBundle\Controller;

use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\AjaxBundle\Controller\BaseFilterController;

/**
 * InvoiceController
 */
class InvoiceController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.filter.namespace.report.invoice';

    /**
     *  indexAction
     * 
     * @return html Description
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $period = $session->get('invoicePeriod', 30);


        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'period' => $period
        ));
    }

    /**
     *  showAction
     * 
     * @param int,string $period 30,60,120,180,181,court,pay
     * 
     * @return html Description
     */
    public function showAction($period)
    {
        $session = $this->get('session');

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        if ($period != $session->get('invoicePeriod')) {
            $this->clearPaginator($filterNamespace);
            $session->set('invoicePeriod', $period);
        }

        $em = $this->getDoctrine()->getManager();
        /** @var ITDoors/ControllingBundle/Entity/InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        switch ($period) {
            case 30:
                $entities = $invoice->getInvoicePeriod(1, 30);
                $count = $invoice->getInvoicePeriodCount(1, 30);
                $sum = $invoice->getInvoicePeriodSum(1, 30);
                break;
            case 60:
                $entities = $invoice->getInvoicePeriod(31, 60);
                $count = $invoice->getInvoicePeriodCount(31, 60);
                $sum = $invoice->getInvoicePeriodSum(31, 60);
                break;
            case 120:
                $entities = $invoice->getInvoicePeriod(61, 120);
                $count = $invoice->getInvoicePeriodCount(61, 120);
                $sum = $invoice->getInvoicePeriodSum(61, 120);
                break;
            case 180:
                $entities = $invoice->getInvoicePeriod(121, 180);
                $count = $invoice->getInvoicePeriodCount(121, 180);
                $sum = $invoice->getInvoicePeriodSum(121, 180);
                break;
            case 181:
                $entities = $invoice->getInvoicePeriod(181, 0);
                $count = $invoice->getInvoicePeriodCount(181, 0);
                $sum = $invoice->getInvoicePeriodSum(181, 0);
                break;
            case 'court':
                $entities = $invoice->getInvoiceCourt();
                $count = $invoice->getInvoiceCourtCount();
                $sum = $invoice->getInvoiceCourtSum();
                break;
            case 'pay':
                $entities = $invoice->getInvoicePay();
                $count = $invoice->getInvoicePayCount();
                $sum = $invoice->getInvoicePaySum();
                break;
        }

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $entities->setHint('knp_paginator.count', $count);
        $pagination = $paginator->paginate(
            $entities, $page, 20
        );
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
     * @param string    $block
     * 
     * @return html Description
     */
    public function invoiceAction($invoiceid, $block)
    {
        $session = $this->get('session');
        $session->set('invoiceBlock', $block);
        $session->set('invoiceid', $invoiceid);

        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:invoice.html.twig', array(
                'invoiceid' => $invoiceid,
                'block' => $block,
                'invoice' => $invoiceObj
        ));
    }

    /**
     *  invoiceshowAction
     * 
     * @param int $block
     * 
     * @return html Description
     */
    public function invoiceshowAction($block)
    {
        $session = $this->get('session');
        $session->set('invoiceBlock', $block);
        $invoiceid = $session->get('invoiceid');
        $em = $this->getDoctrine()->getManager();
        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);
        $entitie = '';
        $organizationId = '';
        $dogovor = '';
        switch ($block) {
            case 'invoice':
                $dogovor = $invoiceObj->getDogovor();
                $organization = $dogovor->getOrganization();
                if (empty($organization)) {
                    $organization = $invoiceObj->getOrganization();
                }
                $organizationId = $organization->getId();
                $entitie = $invoiceObj;
                break;
            case 'organization':
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                $organizationId = $dogovor->getCustomerId() ? $dogovor->getCustomerId() : ($dogovor->getOrganization() ? $dogovor->getOrganization()->getId() : $invoiceObj->getOrganization()->getId());
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Organization')
                    ->find($organizationId);
                break;
            case 'responsible':
                $entitie = $em->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                    ->findBy(array('invoiceId' => $invoiceid));
                break;
            case 'dogovor':
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                ;
                break;
            case 'contacts':
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                $organizationId = $dogovor->getCustomerId() ? $dogovor->getCustomerId() : ($dogovor->getOrganization() ? $dogovor->getOrganization()->getId() : $invoiceObj->getOrganization()->getId());
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsContactBundle:ModelContact')
                    ->findBy(array('modelName' => 'organization', 'modelId' => $organizationId));
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());

                $organizationId = $dogovor->getCustomerId() ? $dogovor->getCustomerId() : ($dogovor->getOrganization() ? $dogovor->getOrganization()->getId() : $invoiceObj->getOrganization()->getId());
                break;
            case 'history':
                $entitie = '';
                break;
        }

        return $this->render('ITDoorsControllingBundle:Invoice:table' . $block . '.html.twig', array(
                'entitie' => $entitie,
                'block' => $block,
                'organizationId' => $organizationId,
                'dogovor' => $dogovor,
                'invoice' => $invoiceObj
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

}
