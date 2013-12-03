<?php

namespace Lists\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesDispatcherController extends SalesController
{
    protected $baseRoute = 'lists_sales_dispatcher_team_index';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';
}
