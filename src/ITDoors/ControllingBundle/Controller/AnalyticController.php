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
     *  listAction
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
                'entities' => $result,
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
