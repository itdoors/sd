<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SDCalendarBundle:Default:index.html.twig', array('name' => $name));
    }
}
