<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDCalendarBundle:Sales:index.html.twig');
    }
}
