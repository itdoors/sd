<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\OperBundle\Entity\CommentOrganizer;
use ITDoors\OperBundle\Entity\OperOrganizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperStatisticController class
 *
 * Default controller for oper page
 */
class OperStatisticController extends Controller
{

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {

        $statistic = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        return $this->render('ITDoorsOperBundle:Statistic:index.html.twig', array (

        ));
    }

}
