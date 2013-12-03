<?php

namespace Lists\CityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ListsCityBundle:Default:index.html.twig', array('name' => $name));
    }
}
