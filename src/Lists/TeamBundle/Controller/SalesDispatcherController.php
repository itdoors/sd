<?php

namespace Lists\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SalesDispatcherController
 */
class SalesDispatcherController extends SalesController
{
    protected $baseRoute = 'lists_sales_dispatcher_team_index';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';
}
