<?php

namespace Lists\OrganizationBundle\Controller;

use Lists\OrganizationBundle\Controller\SalesController as BaseController;

class SalesAdminController extends BaseController
{
    protected $filterNamespace = 'organization.sales.filters';
    protected $filterForm = 'organizationSalesFilterForm';
    protected $baseRoute = 'lists_sales_organization_index';


}