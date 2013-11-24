<?php

namespace SD\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDDashboardBundle:Default:index.html.twig');
    }
}
