<?php

namespace Lists\OrganizationBundle\Controller;

use Lists\OrganizationBundle\ListsOrganizationBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesController extends Controller
{
    protected $filterNamespace = 'organization.sales.filters';

    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

      return $this->render('ListsOrganizationBundle:Sales:index.html.twig', array(
          'pagination' => $pagination,
          'filterForm' => $filterForm->createView()
      ));
    }

    /**
     * Executes new action
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('organizationSalesForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_organization_show', array(
                'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:Sales:new.html.twig', array(
            'filterForm' => $form->createView()
        ));
    }

    /**
     * Executes show action
     */
    public function showAction($id)
    {
        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization= $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        return $this->render('ListsOrganizationBundle:Sales:show.html.twig', array(
            'organization' => $organization
        ));
    }

    /**
     * Processes filters for view
     */
    public function processFilters()
    {
        $filterForm = $this->createForm('organizationFilterForm');

        $filterForm->bind($this->getFilters());

        return $filterForm;
    }

    /**
     * Executes filter action
     */
    public function filterAction()
    {
        $filters = $this->get('request')->request->get('organizationFilterForm');

        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->get('session');

        $session->set($this->filterNamespace, $filters);

        return $this->redirect($this->generateUrl('lists_sales_organization_index'));
    }

    public function getFilters()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->get('session');

        return $session->get($this->filterNamespace);
    }
}

