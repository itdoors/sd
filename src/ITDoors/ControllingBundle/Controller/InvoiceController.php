<?php

namespace ITDoors\ControllingBundle\Controller;

use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;

/**
 * InvoiceController
 */
class InvoiceController extends BaseController
{

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
        $session->set('invoicePeriod', $period);

        $em = $this->getDoctrine()->getManager();
        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        switch ($period) {
            case 30:
                $entities = $invoice->getInvoicePeriod(1, 30);
                break;
            case 60:
                $entities = $invoice->getInvoicePeriod(31, 60);
                break;
            case 120:
                $entities = $invoice->getInvoicePeriod(61, 120);
                break;
            case 180:
                $entities = $invoice->getInvoicePeriod(121, 180);
                break;
            case 181:
                $entities = $invoice->getInvoicePeriod(181, 0);
                break;
            case 'court':
                $entities = $invoice->getInvoiceCourt();
                break;
            case 'pay':
                $entities = $invoice->getInvoicePay();
                break;
        }

        return $this->render('ITDoorsControllingBundle:Invoice:show.html.twig', array(
                'entities' => $entities
        ));
    }

    /**
     *  invoiceAction
     * 
     * @param int $invoiceid
     * 
     * @return html Description
     */
    public function invoiceAction($invoiceid)
    {
        $session = $this->get('session');
        $block = $session->get('invoiceBlock', 'invoice');
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
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                $organizationId = $dogovor->getCustomerId() ? $dogovor->getCustomerId() : ($dogovor->getOrganization() ? $dogovor->getOrganization()->getId() : $invoiceObj->getOrganization()->getId());
                $organization = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Organization')
                    ->find($organizationId);
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
                $entitie = '';
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
                    ->findBy(array('modelName' => 'organization', 'modelId' => $organizationId, 'owner' => $this->getUser()->getId()));
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

}
