<?php

namespace Lists\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        return $this->render('ListsContactBundle:Sales:index.html.twig', array(
            'baseTemplate' => $this->baseTemplate
        ));
    }

    public function organizationAction()
    {
        $user = $this->getUser();

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($user->getId(), null)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:Sales:organization.html.twig', array(
            'organizationContacts' => $organizationContacts,
        ));
    }
}
