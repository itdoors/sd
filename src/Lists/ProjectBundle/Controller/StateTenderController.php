<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\ProjectBundle\Entity\StateTender;
use Lists\ProjectBundle\Services\ProjectService;
use Lists\ProjectBundle\Entity\ManagerProjectType;

/**
 * Class StateTenderController
 */
class StateTenderController extends ProjectBaseController
{
    protected $filterNamespace = 'state_tender';
    protected $createForm = 'stateTenderForm';
    protected $nameEntity = 'StateTender';

    /**
     * Executes create action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction (Request $request)
    {
        /** @var \SD\UserBundle\Entity\User $user */
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

            $fileTypes = $em->getRepository('ListsProjectBundle:ProjectFileType')
                ->findBy(array ('group' => 'gos_tender'));

            /** @var StateTender $object */
            $object = $form->getData();
            $object->setUserCreated($user);

            foreach ($fileTypes as $type) {
                $file = new \Lists\ProjectBundle\Entity\FileProject();
                $file->setProject($object);
                $file->setType($type);
                $file->setUser($user);
                $em->persist($file);
            }

            $managerProject = new ManagerProjectType();
            $managerProject->setPart(100);
            $managerProject->setUser($user);
            $managerProject->setProject($object);
            $em->persist($managerProject);

            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_project_'.$this->filterNamespace.'_show', array (
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':create.html.twig', array (
                'form' => $form->createView()
        ));
    }
    /**
     * indexAction
     */
    public function indexAction ()
    {
        throw $this->createNotFoundException();
    }
    /**
     * listAction
     */
    public function listAction ()
    {
        throw $this->createNotFoundException();
    }
    /**
     * indexStatusAction
     * 
     * @param string $status active|closed
     *
     * @return Response
     */
    public function indexStatusAction ($status)
    {
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($this->getUser());
        $method = 'canSee'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':indexStatus.html.twig', array (
                'access' => $access,
                'status' => $status
        ));
    }
    /**
     * listStatusAction
     * 
     * @param string $status active|closed
     *
     * @return Response
     */
    public function listStatusAction ($status)
    {
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameEntity).'_'.$status;
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var ProjectService $service */
        $service = $this->get('lists_project.service');
        $access = $service->checkAccess($user);

        $method = 'canSee'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }
        $methodCanSeeAll = 'canSeeAll'.$this->nameEntity;
        if ($access->$methodCanSeeAll()) {
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

        /** @var \Lists\HandlingBundle\Entity\ProjectGosTenderRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:'.$this->nameEntity);

        $methodRepository = 'getList'.$this->nameEntity;
        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->$methodRepository($user, $status);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            10
        );

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':listStatus.html.twig', array(
                'filterNamespace' => $filterNamespace,
                'pagination' => $pagination,
                'access' => $access
            ));
    }
    /**
     * Executes showParticipants
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showParticipantsAction ($id)
    {
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
        $participans = $object->getParticipans();

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':Tab/participants.html.twig', array (
                'participans' => $participans,
                'object' => $object,
                'access' => $access
        ));
    }
    /**
     * Executes showDocumentsAction
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDocumentsAction ($id)
    {
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

        return $this->render('ListsProjectBundle:'.$this->nameEntity.':Tab/documents.html.twig', array (
                'object' => $object,
                'access' => $access
        ));
    }
}
