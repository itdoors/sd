<?php

namespace SD\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    /**
     * indexAction
     *
     * @param sting $name
     *
     * @return string
     */
    public function indexAction($name)
    {
        return $this->render('SDCommonBundle:Default:index.html.twig', array('name' => $name));
    }
}
