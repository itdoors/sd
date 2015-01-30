<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\HandlingBundle\Entity\HandlingUser;
use Doctrine\ORM\EntityManager;

/**
 * Class ProjectController
 */
class ProjectController extends Controller
{
    protected $filterNamespace = 'project';
    protected $createForm = 'projectForm';
    protected $nameConroller = 'Project';
    protected $aliasProjectType = 'project';
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

            /** @var \Lists\HandlingBundle\Entity\ProjectGosTender $object */
            $object = $form->getData();

            $project = $object->getProject();
            $project->setUser($user);
            $project->setType($type);
            $em->persist($project);

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
     *
     * @return Response
     */
    public function indexAction ()
    {
        /** @var HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($this->getUser());
        $method = 'canSee'.$this->nameConroller;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':index.html.twig', array (
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
        $filterNamespace = $this->filterNamespace.'_'.strtolower($this->nameConroller);
        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var HandlingService $service */
        $service = $this->get('lists_handling.service');
        $access = $service->checkAccess($user);

        $method = 'canSee'.$this->nameConroller;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
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
            ->getRepository('ListsHandlingBundle:Project'.$this->nameConroller);

        $methodRepository = 'getList'.$this->nameConroller;
        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->$methodRepository();

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            10
        );

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':list.html.twig', array(
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
        $em = $this->getDoctrine()->getManager();
        /** @var BaseService $baseService */
//        $baseService = $this->get('itdoors_common.base.service');
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
        $organization = $object->getProject()->getOrganization();
        $serviceOrganization = $this->get('lists_organization.service');
        $accessOrganization = $serviceOrganization->checkAccess($user, $organization);

        $lookups = $em->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        return $this->render('ListsHandlingBundle:'.$this->nameConroller.':show.html.twig', array (
                'accessOrganization' => $accessOrganization,
                'organization' => $organization,
                'object' => $object,
                'access' => $access,
                'lookups' => $lookups
        ));
    }
}
