<?php

namespace SD\ServiceDeskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    /**
     * indexAction
     * 
     * @param string $name
     * 
     * @return string
     */
    public function indexAction($name)
    {
        return $this->render('SDServiceDeskBundle:Default:index.html.twig', array('name' => $name));
    }
}
