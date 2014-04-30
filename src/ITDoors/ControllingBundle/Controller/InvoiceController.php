<?php

namespace ITDoors\ControllingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;

/**
 * InvoiceController
 */
class InvoiceController extends BaseController
{

    protected $filterNamespace = 'handling.sales.filters';
    protected $filterFormName = 'handlingSalesFilterForm';
    protected $baseRoute = 'invoice';
    protected $baseRoutePrefix = 'controlling';
    protected $baseTemplate = 'Invoice';
    protected $wizardOrganizationNamespace = 'sales.wizard.organization';
    protected $wizardHandlingNamespace = 'sales.wizard.handling';

    /**
     *  indexAction
     * 
     * @return html Description
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        $entities30 = $invoice->getInvoicePeriod(1, 30);
        $entities60 = $invoice->getInvoicePeriod(31, 60);
        $entities120 = $invoice->getInvoicePeriod(61, 120);
        $entities180 = $invoice->getInvoicePeriod(121, 180);
        $entities181 = $invoice->getInvoicePeriod(181, 0);
        $entitiescourt = $invoice->getInvoiceCourt();

        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'entities30' => $entities30,
                'entities60' => $entities60,
                'entities120' => $entities120,
                'entities180' => $entities180,
                'entities181' => $entities181,
                'entitiescourt' => $entitiescourt,
                'entities' => array(),
        ));
    }

}
