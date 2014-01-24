<?php

namespace Lists\DogovorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DogovorController extends Controller
{
    protected $baseRoutePrefix = 'dogovor';

    public function indexAction()
    {
        return $this->render('ListsDogovorBundle:Dogovor:index.html.twig', array(
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    public function newAction()
    {
        return $this->render('ListsDogovorBundle:Dogovor:new.html.twig', array(
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
}
