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

    protected $baseRoute = 'invoice';
    protected $baseRoutePrefix = 'controlling';
    protected $baseTemplate = 'Invoice';

    /**
     *  indexAction
     * 
     * @return html Description
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $period = $session->get('invoicePeriod', 30);

        $em = $this->getDoctrine()->getEntityManager();
        /** @var InvoiceRepository */
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
            case 0:
                $entities = $invoice->getInvoiceCourt();
                break;
        }

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'entities' => $entities,
                'period' => $period
        ));
    }

    /**
     *  showAction
     * 
     * @param int $period 0,30,60,120,180,181,0
     * 
     * @return html Description
     */
    public function showAction($period)
    {

        $session = $this->get('session');
        $session->set('invoicePeriod', $period);

        $em = $this->getDoctrine()->getEntityManager();
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
            case 0:
                $entities = $invoice->getInvoiceCourt();
                break;
        }

        return $this->render('ITDoorsControllingBundle:Invoice:show.html.twig', array(
                'entities' => $entities
        ));
    }

}
