<?php

namespace Lists\ContactBundle\Controller;

use SD\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends BaseController
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
        $page = $this->get('request')->query->get('page', 1);

        $user = $this->getUser();

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($user->getId(), $organizationId);

        if (!$organizationId)
        {
            /** @var \Knp\Component\Pager\Paginator $paginator */
            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $organizationContacts,
                $page,
                20
            );
        }
        else
        {
            $pagination = $organizationContacts->getResult();
        }

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'pagination' => $pagination,
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
