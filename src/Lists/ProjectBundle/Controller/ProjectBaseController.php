<?php

namespace Lists\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Lists\ProjectBundle\Services\ProjectService;
use SD\UserBundle\Entity\User;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use ITDoors\CommonBundle\Services\BaseService;

/**
 * Class ProjectBaseController
 */
class ProjectBaseController extends Controller
{
    protected $filterNamespace = 'project';
    protected $createForm = 'projectForm';
    protected $nameEntity = null;
    /**
     * Executes create action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction (Request $request)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        /** @var User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $method = 'canCreate'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm($this->createForm);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            
            $object = $form->getData();

            $managerProject = new ManagerProjectType();
            $managerProject->setPart(100);
            $managerProject->setUser($user);
            $managerProject->setProject($object);
            $em->persist($managerProject);

            $object->setUserCreated($user);

            $em->persist($object);
            $em->flush();
            // костыль для поля boolean set null (нужно будет удалить)
//            $db = $em->getConnection();
//            $stmt = $db->prepare('UPDATE "public".project_gos_tender SET "is_participation" = NULL WHERE id = :id');
//            $stmt->execute(array (':id' => $object->getId()));

            return $this->redirect($this->generateUrl('lists_project_'.strtolower($this->nameEntity).'_show', array (
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':create.html.twig', array (
                'form' => $form->createView()
        ));
    }

    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction ()
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        /** @var User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);
        $method = 'canSeeList'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':index.html.twig', array (
                'access' => $access
        ));
    }
    /**
     * gosListAction
     *
     * @return Response
     */
    public function listAction ()
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameEntity);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $method = 'canSeeList'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }
        if ($access->canSeeAllGosTender()) {
            $user = null;
        }
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
//        $filters = $baseFilter->getFilters($filterNamespace);

//        if (empty($filters)) {
//            $filters['isFired'] = 'No fired';
//            $this->setFilters($filterNamespace, $filters);
//        }

        $page = $baseFilter->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:'.$this->nameEntity);

        $methodRepository = 'getList'.$this->nameEntity;
        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->$methodRepository($user);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            10
        );

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':list.html.twig', array(
                'filterNamespace' => $filterNamespace,
                'pagination' => $pagination,
                'access' => $access
            ));
    }
    /**
     * Executes show action
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction ($id)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var BaseService $baseService */
//        $baseService = $this->get('itdoors_common.base.service');
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository('ListsProjectBundle:'.$this->nameEntity);
        $methodGet = 'get'.$this->nameEntity;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user, $object);

        $methodSee = 'canSee'.$this->nameEntity;
        if (!$access->$methodSee()) {
            throw $this->createAccessDeniedException();
        }
        $organization = $object->getOrganization();
        $serviceOrganization = $this->get('lists_organization.service');
        $accessOrganization = $serviceOrganization->checkAccess($user, $organization);

        $lookups = $em->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':show.html.twig', array (
                'accessOrganization' => $accessOrganization,
                'organization' => $organization,
                'object' => $object,
                'access' => $access,
                'lookups' => $lookups
        ));
    }
    /**
     * Executes showParticipants
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction ($id)
    {
        if (!$this->nameEntity) {
            $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository('ListsProjectBundle:'.$this->nameEntity);
        $methodGet = 'get'.$this->nameEntity;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user, $object);

        $methodSee = 'canSee'.$this->nameEntity;
        if (!$access->$methodSee()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':Tab/edit.html.twig', array (
                'object' => $object,
                'access' => $access
        ));
    }
}
