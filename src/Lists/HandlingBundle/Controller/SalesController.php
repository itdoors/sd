<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SD\CommonBundle\Controller\BaseFilterController as BaseController;

class SalesController extends BaseController
{
    protected $filterNamespace = 'handling.sales.filters';
    protected $filterFormName = 'handlingSalesFilterForm';
    protected $baseRoute = 'lists_sales_handling_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        // Get organization filter
        $filters = $this->getFilters();

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

        $canAddNew = $this->getFilterValueByKey('organization_id') ? true : false;

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate,
            'canAddNew' => $canAddNew
        ));
    }

    /**
     * Executes new action
     */
    public function newAction(Request $request)
    {
        // Get organization filter
        $filters = $this->getFilters();

        if (!isset($filters['organization_id']) || !$filters['organization_id'])
        {
            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_index'));
        }

        $organizationId = $filters['organization_id'];

        $this->get('sd.security_access')->hasAccessToOrganizationAndThrowException($organizationId);

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $user = $this->getUser();

        $form = $this->createForm('handlingSalesForm');

        $form
            ->add('organization', 'text', array(
                'disabled' => true,
                'data' => (string) $organization
            ))
            ->add('user', 'text', array(
                'disabled' => true,
                'data' => (string) $user
            ))
        ;

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\HandlingBundle\Entity\Handling $object */
            $object = $form->getData();

            $object->setUser($user);
            $object->setCreatedatetime(new \DateTime());
            $object->setOrganization($organization);
            $object->addUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_handling_show', array(
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':new.html.twig', array(
            'form' => $form->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate
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

        return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_handling_index'));
    }

    /**
     * Executes show action
     */
    public function showAction($id, Request $request)
    {
        $this->get('sd.security_access')->hasAccessToHandlingAndThrowException($id);

        /** @var \Lists\HandlingBundle\Entity\Handling $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getHandlingShow($id);

        $handlingServiceObjects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        $handlingServices = array();

        foreach($handlingServiceObjects as $hs)
        {
            $handlingServices[] = $this->serializeObject($hs);
        }

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'handling' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'handlingServices' => $handlingServices
        ));
    }

    public function messagesListAction($handlingId)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessageRepository $messagesRepository */
        $messagesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessage');

        $messages = $messagesRepository->getMessagesByHandlingId($handlingId);

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':messagesList.html.twig', array(
            'messages' => $messages,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));

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

    /**
     * Serialize object to json. temporary solution
     *
     * @param object $object
     * @param string $idMethod
     * @param string $method
     *
     * @return mixed[]
     */
    public function serializeObject($object, $idMethod = '', $method = '')
    {
        $id = $idMethod ? $object->$idMethod() : $object->getId();
        $string = $method ? $object->$method() : (string) $object;

        return array(
            'id' => $id,
            'value' => $id,
            'name' => $string,
            'text' => $string
        );
    }
}

