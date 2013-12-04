<?php

namespace SD\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseFilterController extends Controller
{
    protected $filterNamespace = 'base.sales.filters';
    protected $filterFormName = 'baseSalesFilterForm';
    protected $baseRoute = 'lists_sales_base_index';

    /**
     * Executes filter clear action
     */
    public function filterClearAction()
    {
        $this->clearFilters();

        return $this->redirect($this->generateUrl($this->baseRoute));
    }

    /**
     * Processes filters for view
     */
    public function processFilters()
    {
        $filterForm = $this->createForm($this->filterFormName);

        $filterForm->bind($this->getFilters());

        return $filterForm;
    }

    /**
     * Executes filter action
     */
    public function filterAction()
    {
        $filters = $this->get('request')->request->get($this->filterFormName);

        if (!isset($filters['reset']))
        {
            $this->setFilters($filters);
        }
        else
        {
            $this->clearFilters();
        }

        return $this->redirect($this->generateUrl($this->baseRoute));
    }

    public function setFilters($filters)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->get('session');

        $session->set($this->filterNamespace, $filters);
    }

    public function getFilters()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->get('session');

        return $session->get($this->filterNamespace);
    }

    public function clearFilters()
    {
        $this->setFilters(array());
    }

    public function addToFilters($key, $value)
    {
        $filters = $this->getFilters();

        $filters[$key] = $value;

        $this->setFilters($filters);
    }

    public function removeFromFilters($key)
    {
        $filters = $this->getFilters();

        if (isset($filters[$key]))
        {
            unset($filters[$key]);
        }

        $this->setFilters($filters);
    }

    public function getFilterValueByKey($key, $default = null)
    {
        $filters = $this->getFilters();

        if (isset($filters[$key]))
        {
            return $filters[$key];
        }

        return $default;
    }
}
