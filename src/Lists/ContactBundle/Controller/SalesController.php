<?php

namespace Lists\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    public function organizationAction($organizationId)
    {
        $user = $this->getUser();

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($user->getId(), $organizationId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'organizationContacts' => $organizationContacts,
            'organizationId' => $organizationId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    public function handlingAction($handlingId)
    {
        $user = $this->getUser();

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($user->getId(), $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':handling.html.twig', array(
            'handlingContacts' => $handlingContacts,
            'handlingId' => $handlingId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
}
