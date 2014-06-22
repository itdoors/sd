<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Lists\OrganizationBundle\Controller\SalesController;

/**
 * Class SalesController
 */
class CompetitorsController extends SalesController
{

    protected $filterNamespace = 'organization.competitors.filters';
//    protected $filterFormName = 'organizationSalesFilterForm';
//    protected $baseRoute = 'lists_sales_organization_index';
    protected $baseRoutePrefix = 'competitors';
    protected $baseTemplate = 'Competitors';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $namespase = $this->filterNamespace;
        $filter = $this->filterFormName;

        return $this->render('ListsOrganizationBundle:Competitors:index.html.twig', array(
                'namespase' => $namespase,
                'filter' => $filter,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query */
        $organizationsQuery = $organizationsRepository->getCompetitors(null, $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':list.html.twig', array(
                'pagination' => $pagination,
                'namespase' => $namespase,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
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
        if ($this->getUser()->hasRole('ROLE_DOGOVORADMIN')) {
            $form = $this->createForm('organizationDogovorAdminForm');
        } else {
            $form = $this->createForm('organizationSalesForm');
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->addUser($user);
            $organization->setCreator($user);

            $em = $this->getDoctrine()->getManager();
            if (!$organization->getLookup || !$this->getUser()->hasRole('ROLE_DOGOVORADMIN')) {
                $lookup = $em->getRepository('ListsLookupBundle:Lookup')
                    ->find(61);
                $organization->setLookup($lookup);
            }

            $em->persist($organization);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                        'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':new.html.twig', array(
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
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
        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $ur = $this->container->get('sd_user.repository');

        $organizationUsers = $ur->getOrganizationUsersQuery($organizationId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':organizationUsers.html.twig', array(
                'organizationUsers' => $organizationUsers,
                'organizationId' => $organizationId
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
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                        'id' => $organization->getParentId()
            )));
        }

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'organization' => $organization,
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'managerForm' => $managerForm->createView()
        ));
    }
}
