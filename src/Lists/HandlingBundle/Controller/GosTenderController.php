<?php

namespace Lists\HandlingBundle\Controller;

use Lists\HandlingBundle\Controller\ProjectController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\ProjectBundle\Entity\Project;
use Lists\ProjectBundle\Entity\GosTender;

/**
 * Class GosTenderController
 */
class GosTenderController extends ProjectController
{
    protected $filterNamespace = 'project';
    protected $createForm = 'gosTenderForm';
    protected $nameConroller = 'GosTender';
    protected $aliasProjectType = 'gos_tender';

    /**
     * Executes create action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = new GosTender();
        $project->setNumber('111');
        $em->persist($project);
         $em->flush();
         
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());

        $method = 'canCreate'.$this->nameConroller;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm($this->createForm);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            $type = $em->getRepository('ListsHandlingBundle:HandlingType')
                ->findOneBy(array('alias' => $this->aliasProjectType));
            $managerProject = $em->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array ('lukey' => 'manager_project'));
            $fileTypes = $em->getRepository('ListsHandlingBundle:ProjectFileType')
                ->findBy(array ('group' => 'gos_tender'));

            /** @var \Lists\HandlingBundle\Entity\ProjectGosTender $object */
            $object = $form->getData();

            $project = $object->getProject();
            $project->setUser($user);
            $project->setType($type);
            $em->persist($project);

            foreach ($fileTypes as $type) {
                $file = new \Lists\HandlingBundle\Entity\ProjectFile();
                $file->setProject($project);
                $file->setType($type);
                $em->persist($file);
            }

            $manager = new HandlingUser();
            $manager->setUser($user);
            $manager->setLookup($managerProject);
            $manager->setPart(100);
            $manager->setHandling($project);
            $em->persist($manager);

            $em->persist($object);
            $em->flush();
            // костыль для поля boolean set null (нужно будет удалить)
            $db = $em->getConnection();
            $stmt = $db->prepare('UPDATE "public".project_gos_tender SET "is_participation" = NULL WHERE id = :id');
            $stmt->execute(array (':id' => $object->getId()));

            return $this->redirect($this->generateUrl('lists_project_'.strtolower($this->nameConroller).'_show', array (
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':create.html.twig', array (
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
        /** @var HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());
        $method = 'canSeeList'.$this->nameConroller;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':indexStatus.html.twig', array (
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
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameConroller);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user);

        $method = 'canSeeList'.$this->nameConroller;
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

        /** @var \Lists\HandlingBundle\Entity\ProjectGosTenderRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Project'.$this->nameConroller);

        $methodRepository = 'getList'.$this->nameConroller;
        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->$methodRepository($user, $status);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            10
        );

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':listStatus.html.twig', array(
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

        $repository = $em->getRepository('ListsHandlingBundle:Project'.$this->nameConroller);
        $methodGet = 'get'.$this->nameConroller;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user, $object->getProject());

        if (!$access->canSee()) {
            throw $this->createAccessDeniedException();
        }
        $participans = $object->getParticipans();

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':Tab/participants.html.twig', array (
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

        $repository = $em->getRepository('ListsHandlingBundle:Project'.$this->nameConroller);
        $methodGet = 'get'.$this->nameConroller;
        $object = $repository->$methodGet($id);

        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user, $object->getProject());

        if (!$access->canSee()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':Tab/documents.html.twig', array (
                'object' => $object,
                'access' => $access
        ));
    }
}
