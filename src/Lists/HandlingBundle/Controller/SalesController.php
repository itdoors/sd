<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SD\CommonBundle\Controller\BaseFilterController as BaseController;

class SalesController extends BaseController
{
    protected $filterNamespace = 'handling.sales.filters';
    protected $filterForm = 'handlingSalesFilterForm';
    protected $baseRoute = 'lists_sales_handling_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        // Get organization filter

        $filters = $this->getFilters();

        if (!isset($filters['organization_id']) || !$filters['organization_id'])
        {
            return $this->redirect($this->generateUrl('lists_sales_organization_index'));
        }

        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery($user->getId(), $filters);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $handlingQuery,
            $page,
            20
        );

        return $this->render('ListsHandlingBundle:Sales:index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ));
    }

    /**
     * Executes new action
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('handlingSalesForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\HandlingBundle\Entity\Handling $object */
            $object = $form->getData();

            $user = $this->getUser();

            $object->setUser($user);
            $object->setCreatedatetime(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_handling_show', array(
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsHandlingBundle:Sales:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
    * Execute addOrganizationFilter action
    */
    public function addOrganizationFilterAction($organization_id)
    {
        $filters = $this->getFilters();

        $filters['organization_id'] = $organization_id;

        $this->setFilters($filters);

        return $this->redirect($this->generateUrl('lists_sales_handling_index'));
    }

    /**
     * Executes show action
     */
    public function showAction($id, Request $request)
    {
        /** @var \Lists\HandlingBundle\Entity\Handling $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getHandlingShow($id);

        return $this->render('ListsHandlingBundle:Sales:show.html.twig', array(
            'handling' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    public function messagesListAction($handlingId)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $messagesRepository */
        $messagesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        $messages = $messagesRepository->getMessagesByHandlingId($handlingId);

        return $this->render('ListsHandlingBundle:Sales:messagesList.html.twig', array(
            'messages' => $messages,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));

    }

    public function clearFilters()
    {
        $filters = $this->getFilters();

        $organizationId = $filters['organization_id'];

        $this->setFilters(array('organization_id' => $organizationId));
    }

    /**
     * Renders ohandlingUsers list
     */
    public function handlingUsersAction($handlingId)
    {
        /** @var \SD\UserBundle\Entity\UserRepository $ur*/
        $ur = $this->container->get('sd_user.repository');

        $handlingUsers = $ur->getHandlingUsersQuery($handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate. ':handlingUsers.html.twig', array(
                'handlingUsers' => $handlingUsers,
                'handlingId' => $handlingId
            ));
    }

}

