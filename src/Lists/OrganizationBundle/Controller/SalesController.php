<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;

/**
 * Class SalesController
 */
class SalesController extends BaseController
{
    protected $filterNamespace = 'organization.sales.filters';
    protected $filterFormName = 'organizationSalesFilterForm';
    protected $baseRoute = 'lists_sales_organization_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $namespaseFilter = $this->filterFormName;
        $filters = $this->getFilters($namespaseFilter);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespaseFilter, $filters);
        }
        
        //$filterForm = $this->getFilters($namespaseFilter);

        $page = $this->get('request')->query->get('page', 1);

        //$filterForm = $this->processFilters();

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

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'pagination' => $pagination,
            'namespaseFilter' => $namespaseFilter,
           // 'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('organizationSalesForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->addUser($user);
            $organization->setCreator($user);

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
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $this->get('sd.security_access')->hasAccessToOrganizationAndThrowException($id);

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization= $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                    'id' => $organization->getParentId()
                )));
        }

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
     *
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
