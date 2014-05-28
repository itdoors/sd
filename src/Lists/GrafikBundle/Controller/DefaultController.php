<?php

namespace Lists\GrafikBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ListsGrafikBundle:Default:index.html.twig', array('name' => $name));
    }
}
