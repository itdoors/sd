<?php

namespace Lists\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
    public function indexAction()
    {
        return $this->render('ListsContactBundle:Sales:index.html.twig');
    }
}
