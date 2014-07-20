<?php

namespace ITDoors\HistoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ITDoorsHistoryBundle:Default:index.html.twig', array('name' => $name));
    }
}
