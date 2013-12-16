<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SD\CommonBundle\Controller\BaseFilterController as BaseController;
use Lists\HandlingBundle\Entity\HandlingResult;
use Lists\ContactBundle\Entity\ModelContact;
use Lists\OrganizationBundle\Entity\Organization;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingMessage;
use Lists\ContactBundle\Entity\ModelContactRepository;

class SalesController extends BaseController
{
    protected $filterNamespace = 'handling.sales.filters';
    protected $filterFormName = 'handlingSalesFilterForm';
    protected $baseRoute = 'lists_sales_handling_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';
    protected $wizardOrganizationNamespace = 'sales.wizard.organization';
    protected $wizardHandlingNamespace = 'sales.wizard.handling';

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
     * Executes list action for dashboard
     */
    public function listAction()
    {
        // Get organization filter
        /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Doctrine\ORM\Query $handlingQuery */
        $handlingQuery = $handlingRepository->getAllForSalesQuery($user->getId(), array());

        $pagination = $handlingQuery->getResult();

        /** @var \Knp\Component\Pager\Paginator $paginator */

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':list.html.twig', array(
                'pagination' => $pagination,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'baseTemplate' => $this->baseTemplate,
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

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($object[0]->getOrganizationId());

        $handlingServiceObjects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        $handlingServices = array();

        foreach($handlingServiceObjects as $hs)
        {
            $handlingServices[] = $this->serializeObject($hs);
        }

        $canEdit = (Boolean) !$object[0]->getIsClosed();

        $isResultClosed = $object['resultSlug'] == HandlingResult::RESULT_CLOSED;

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'handling' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'handlingServices' => $handlingServices,
            'canEdit' => $canEdit,
            'isResultClosed' => $isResultClosed,
            'organization' => $organization
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

    /**
     * Execute wizard step1 action
     */
    public function step1Action(Request $request)
    {
        $isPost = $request->getMethod() == 'POST';

        $initSelection = array();

        if ($isPost)
        {
            $organization = array();

            $organization['organizationId'] = $request->request->get('organizationId');
            $organization['organizationName'] = $request->request->get('organizationName');

            $this->setWizardOrganization($organization);

            if ($this->isValidWizardOrganization())
            {
                return $this->redirect($this->generateUrl('lists_sales_handling_create_step2'));
            }
        }

        if ($this->isValidWizardOrganization())
        {
            $initSelection = array(
                'id' => $this->getWizardOrganizationId(),
                'text' => $this->getWizardOrganizationName()
            );
        }

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate. ':step1.html.twig', array(
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'initSelection' => $initSelection
            ));
    }

    /**
     * Sets wizard organization information to session
     *
     * @param mixed[] $organization
     */
    public function setWizardOrganization($organization)
    {
        $session = $this->get('session');

        $session->set($this->wizardOrganizationNamespace, $organization);
    }

    /**
     * Get wizard organization information to session
     *
     * @return mixed[] $organization
     */
    public function getWizardOrganization()
    {
        $session = $this->get('session');

        $organization = $session->get($this->wizardOrganizationNamespace);

        return $organization;
    }

    /**
     * Execute wizard step2 action
     */
    public function step2Action(Request $request)
    {
        if (!$this->isValidWizardOrganization())
        {
            return $this->redirect($this->generateUrl('lists_sales_handling_create_step1'));
        }

        $form = $this->createForm('modelContactOrganizationWizardForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->beginTransaction();

            $user = $this->getUser();

            /** @var ModelContact $contact */
            $contact = $form->getData();

            try
            {
                if ($this->isNewWizardOrganization())
                {
                    $organization = new Organization();
                    $organization->setCreator($user);
                    $organization->setCreatedatetime(new \DateTime());
                    $organization->setName($this->getWizardOrganizationName());
                    $organization->setShortname($this->getWizardOrganizationName());
                    $organization->setAddress($this->getWizardOrganizationName());

                    $em->persist($organization);
                    $em->flush();

                    $organizationId = $organization->getId();

                    $this->setWizardOrganization(array(
                        'organizationId' => $organization->getId(),
                        'organizationName' => $this->getWizardOrganizationName()
                    ));
                }
                else
                {
                    $organizationId = $this->getWizardOrganizationId();

                    $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
                        ->find($organizationId);
                }

                $contact->setModelId($organizationId);

                $contact->setUser($user);

                $owner = $contact->getOwner();

                if (!$owner)
                {
                    $contact->setOwner($user);
                }

                $em->persist($contact);
                $em->flush();

                $organizationUsers = $organization->getUsers();

                $userExist = false;

                foreach ($organizationUsers as $organizationUser)
                {
                    if ($organizationUser->getId() == $user->getId())
                    {
                        $userExist = true;
                    }
                }

                if (!$userExist)
                {
                    $organization->addUser($user);

                    $em->persist($organization);
                    $em->flush();
                }

                $em->getConnection()->commit();

            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('lists_sales_handling_create_step3'));
        }

        $form
            ->add('organization', 'text', array(
                'disabled' => true,
                'data' => (string) $this->getWizardOrganizationName()
            ));

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate. ':step2.html.twig', array(
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'form' => $form->createView()
            ));
    }

    /**
     * Checks if valid organization data in session
     *
     * @return boolean
     */
    public function isValidWizardOrganization()
    {
        $organization = $this->getWizardOrganization();

        if (!isset($organization['organizationId']) || !isset($organization['organizationName']))
        {
            return false;
        }

        if (!$organization['organizationId'] && !$organization['organizationName'])
        {
            return false;
        }

        return true;
    }

    /**
     * Checks if organization is new
     */
    public function isNewWizardOrganization()
    {
        $organization = $this->getWizardOrganization();

        return $organization['organizationId'] ? false : true;
    }

    /**
     * get wizard organizationId
     */
    public function getWizardOrganizationId()
    {
        $organization = $this->getWizardOrganization();

        return isset($organization['organizationId']) ? $organization['organizationId'] : null;
    }

    /**
     * get wizard organizationName
     */
    public function getWizardOrganizationName()
    {
        $organization = $this->getWizardOrganization();

        return isset($organization['organizationName']) ? $organization['organizationName'] : null;
    }

    /**
     * Execute wizard step3 action
     */
    public function step3Action(Request $request)
    {
        $organizationId = $this->getWizardOrganizationId();

        if (!$organizationId)
        {
            return $this->redirect($this->generateUrl('lists_sales_handling_create_step1'));
        }

        $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $user = $this->getUser();

        $form = $this->createForm('handlingSalesWizardForm');

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

            $this->setWizardHandling($object);

            return $this->redirect($this->generateUrl('lists_sales_handling_create_step4'));

            /*$em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_handling_show', array(
                'id' => $object->getId()
            )));*/
        }

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate. ':step3.html.twig', array(
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'form' => $form->createView()
            ));
    }

    /**
     * Execute wizard step4 action
     */
    public function step4Action(Request $request)
    {
        $handling = $this->getWizardHandling();

        if (!$handling)
        {
            return $this->redirect($this->generateUrl('lists_sales_handling_create_step3'));
        }

        $user = $this->getUser();

        $userIds = array($user->getId());

        $form = $this->createForm('handlingMessageWizardForm');

        $formData = $request->request->get($form->getName());

        $organizationId = $this->getWizardOrganizationId();

        $organization = $this->getDoctrine()->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $form
            ->add('contact', 'entity', array(
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds)
                    {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->andWhere('owner.id in (:ownerIds)')
                            ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId)
                            ->setParameter(':ownerIds', $userIds);
                    }
            ));

        $form
            ->add('contactnext', 'entity', array(
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => false,
                'mapped' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds)
                    {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->andWhere('owner.id in (:ownerIds)')
                            ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId)
                            ->setParameter(':ownerIds', $userIds);
                    }
            ));

        $form
            ->add('status', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingStatus',
                'empty_value' => '',
                'mapped' => false,
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingStatusRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                    }
            ));

        // Bind form
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->beginTransaction();

            try
            {

                $newHandling = new Handling();

                //$newHandling->setStatusId($formData['status']);
                $newHandling->setOrganization($organization);

                $resultId = $handling->getResult() ? $handling->getResult()->getId() : null;
                $statusId = $handling->getStatus() ? $handling->getStatus()->getId() : null;
                $typeId = $handling->getType() ? $handling->getType()->getId() : null;

                $newHandling->setResultId($resultId);
                $newHandling->setStatusId($statusId);
                $newHandling->setTypeId($typeId);

                $newHandling->setUser($user);
                $newHandling->addUser($user);
                $newHandling->setCreatedatetime(new \DateTime());
                $newHandling->setCreatedate($handling->getCreatedate());

                $newHandling->setBudget($handling->getBudget());
                $newHandling->setBudgetClient($handling->getBudgetClient());
                $newHandling->setChance($handling->getChance());
                $newHandling->setDescription($handling->getDescription());

                foreach ($handling->getHandlingServices() as $service)
                {
                    $newService = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingService')
                        ->find($service->getId());

                    $newHandling->addHandlingService($newService);
                }

                //$handling->setS($handling->getResult());

                $em->persist($newHandling);

                $em->flush();

                $data = $form->getData();

                $data->setCreatedatetime(new \DateTime());
                $data->setUser($user);
                $data->setHandling($newHandling);

                $file = $form['file']->getData();

                if ($file)
                {
                    $data->upload();
                }

                $em->persist($data);

                // Insert future
                $type = $this->getDoctrine()
                    ->getRepository('ListsHandlingBundle:HandlingMessageType')
                    ->find($formData['nexttype']);

                $nextDatetime = new \DateTime($formData['nextcreatedate']);
                $contactNext = $formData['contactnext'];
                $descriptionNext = $formData['descriptionnext'];
                $statusId = $formData['status'];

                $handlingMessage = new HandlingMessage();
                $handlingMessage->setCreatedate($nextDatetime);
                $handlingMessage->setCreatedatetime(new \DateTime());
                $handlingMessage->setUser($user);
                $handlingMessage->setHandling($newHandling);
                $handlingMessage->setType($type);
                $handlingMessage->setIsBusinessTrip(isset($formData['next_is_business_trip']) ? true : false);
                $handlingMessage->setAdditionalType(HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE);

                $handlingMessage->setDescription($descriptionNext);

                if ((int) $contactNext)
                {
                    $contact = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                        ->find((int) $contactNext);

                    if ($contact)
                    {
                        $handlingMessage->setContact($contact);
                    }
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($handlingMessage);
                // $em->flush();

                $handling->setLastHandlingDate($data->getCreatedate());
                $handling->setNextHandlingDate($nextDatetime);

                $newHandling->setStatusId($statusId);

                $em->persist($newHandling);

                $em->flush();

                $em->getConnection()->commit();

                $this->setWizardHandling(null);
                $this->setWizardOrganization(array());

                return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix. '_handling_show', array(
                        'id' => $newHandling->getId()
                    )));

            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }
        }

        return $this->render('ListsHandlingBundle:' . $this->baseTemplate. ':step4.html.twig', array(
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'form' => $form->createView()
            ));
    }

    /**
     * Sets wizard handling information to session
     *
     * @param Handling $handling
     */
    public function setWizardHandling($handling)
    {
        $session = $this->get('session');

        $session->set($this->wizardHandlingNamespace, $handling);
    }

    /**
     * Get wizard handling information to session
     *
     * @return Handling $handling
     */
    public function getWizardHandling()
    {
        $session = $this->get('session');

        $handling = $session->get($this->wizardHandlingNamespace);

        return $handling ? $handling : null;
    }
}

