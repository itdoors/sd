<?php

namespace Lists\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ListsProjectBundle:Default:index.html.twig', array('name' => $name));
    }
}
