<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SD\CommonBundle\Controller\BaseFilterController as BaseController;

class SalesController extends BaseController
{
    protected $filterNamespace = 'organization.sales.filters';
    protected $filterFormName = 'organizationSalesFilterForm';
    protected $baseRoute = 'lists_sales_organization_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \SD\UserBundle\Entity\User $user*/
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery($user->getId(), $this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

      return $this->render('ListsOrganizationBundle:' . $this->baseTemplate. ':index.html.twig', array(
          'pagination' => $pagination,
          'filterForm' => $filterForm->createView(),
          'filterFormName' => $this->filterFormName,
          'baseTemplate' => $this->baseTemplate,
          'baseRoutePrefix' => $this->baseRoutePrefix,
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

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate. ':new.html.twig', array(
            'filterForm' => $form->createView(),
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
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

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate. ':show.html.twig', array(
            'organization' => $organization,
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'managerForm' => $managerForm->createView()
        ));
    }

    /**
     * Renders organizationUsers list
     */
    public function organizationUsersAction($organizationId)
    {
        /** @var \SD\UserBundle\Entity\UserRepository $ur*/
        $ur = $this->container->get('sd_user.repository');

        $organizationUsers = $ur->getOrganizationUsersQuery($organizationId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate. ':organizationUsers.html.twig', array(
                'organizationUsers' => $organizationUsers,
                'organizationId' => $organizationId
            ));
    }
}

