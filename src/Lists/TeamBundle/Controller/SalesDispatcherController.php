<?php

namespace Lists\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesDispatcherController extends SalesController
{
    public function indexAction()
    {
        //$teams =

        return $this->render('ListsTeamBundle:SalesDispatcher:index.html.twig');
    }

    public function newAction(Request $request)
    {
        $form = $this->createForm('teamForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\TeamBundle\Entity\Team $object*/
            $object = $form->getData();

            $object->setOwner($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_dispatcher_team_index'));
        }

        return $this->render('ListsTeamBundle:SalesDispatcher:new.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
