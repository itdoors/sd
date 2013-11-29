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

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery($filters['organization_id']);

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
            ->find($id);

        $handlingMessageForm = $this->createForm('handlingMessageForm');

        $handlingMessageForm->handleRequest($request);

        if ($handlingMessageForm->isValid())
        {
            /** @var \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage*/
            $handlingMessage = $handlingMessageForm->getData();

            $user = $this->getUser();
            $handlingMessage->setHandling($object);
            $handlingMessage->setCreatedatetime(new \DateTime());
            $handlingMessage->setUser($user);

            $handlingMessageForm['filepath']->getData()->move('/var/www/', 'test.jpg');

            $em = $this->getDoctrine()->getManager();
            $em->persist($handlingMessage);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_handling_show', array(
                'id' => $id
            )));
        }

        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $messagesRepository */
        $messagesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        $messages = $messagesRepository->getMessagesByHandlingId($id);

        return $this->render('ListsHandlingBundle:Sales:show.html.twig', array(
            'handling' => $object,
            'messages' => $messages,
            'handlingMessageForm' => $handlingMessageForm->createView()
        ));
    }

    public function clearFilters()
    {
        $filters = $this->getFilters();

        $organizationId = $filters['organization_id'];

        $this->setFilters(array('organization_id' => $organizationId));
    }
}

