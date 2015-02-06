<?php

namespace SD\BusinessRoleBundle\Controller;

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
        return $this->render('SDBusinessRoleBundle:Default:index.html.twig', array('name' => $name));
    }
}
