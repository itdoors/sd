<?php

namespace Lists\RegionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ListsRegionBundle:Default:index.html.twig');
    }
}
