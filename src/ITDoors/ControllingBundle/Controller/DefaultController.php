<?php

namespace ITDoors\ControllingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ITDoorsControllingBundle:Default:index.html.twig');
    }
}
