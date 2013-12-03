<?php

namespace Lists\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesAdminController extends SalesController
{
    protected $baseRoute = 'lists_sales_admin_team_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';


    public function editAction($id, Request $request)
    {
        if ($id && $id !=0 )
        {
            $team = $this->getDoctrine()->getRepository('ListsTeamBundle:Team')
                ->find($id);

            $form = $this->createForm('teamForm', $team);
        }
        else
        {
            $form = $this->createForm('teamForm');
        }

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\TeamBundle\Entity\Team $object*/
            $object = $form->getData();

            if (!$object->getId())
            {
                $object->setOwner($this->getUser());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_team_index'));
        }

        return $this->render('ListsTeamBundle:' . $this->baseTemplate . ':edit.html.twig', array(
                'form' => $form->createView(),
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseRoute' => $this->baseRoute,
                'id' => $id
            ));
    }
}
