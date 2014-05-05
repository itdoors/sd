<?php

namespace Lists\MpkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ListsMpkBundle:Default:index.html.twig', array('name' => $name));
    }
}
