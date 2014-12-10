<?php

namespace Lists\CoachBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * CoachReportController
 */
class CoachReportController extends Controller
{
    /**
     * Execute showtabs action
     *
     * @return string
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_COACHADMIN')) {
            return $this->render('ListsCoachBundle:Report:adminIndex.html.twig', array());
        } else {
            return $this->render('ListsCoachBundle:Report:coachIndex.html.twig', array());
        }
    }

    /**
     * Execute list action
     *
     * @return string
     */
    public function listAction()
    {
        return $this->render('ListsCoachBundle:Report:list.html.twig', array(
                        'items' => []
        ));
    }

    /**
     * Execute show action
     *
     * @param int $id
     *
     * @return string
     */
    public function showAction($id)
    {
        return $this->render('ListsCoachBundle:Report:show.html.twig', array());
    }

    /**
     * Execute add action
     *
     * @return string
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm('coachReportForm');
        $form->handleRequest($request);
        return $this->render('ListsCoachBundle:Report:add.html.twig', array(
                        'form' => $form->createView()
        ));
    }

    /**
     * Execute edit action
     *
     * @param int $id
     *
     * @return string
     */
    public function editAction($id)
    {
        return $this->render('ListsCoachBundle:Report:edit.html.twig', array());
    }
}
