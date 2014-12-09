<?php

namespace Lists\CoachBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * CoachTaskController
 */
class CoachTaskController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ListsCoachBundle:Default:index.html.twig', array());
    }
}
