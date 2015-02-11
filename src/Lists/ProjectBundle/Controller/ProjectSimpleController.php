<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\ProjectBundle\Services\ProjectService;
use Lists\ProjectBundle\Entity\ManagerProjectType;
use Lists\ProjectBundle\Entity\ProjectСommercialTender;

/**
 * Class ProjectSimpleController
 */
class ProjectSimpleController extends ProjectBaseController
{
    protected $filterNamespace = 'project_simple';
    protected $createForm = 'projectSimpleForm';
    protected $nameEntity = 'ProjectSimple';

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
            
            $type = $form->get('type')->getData();
            if ($type == 'simple') {
                $type = 'simple';
                $object = $form->getData();
            } elseif ($type == 'commercial_tender') {
                $services = $form->get('services')->getData();
                $object = new ProjectСommercialTender();
                $object->setOrganization($form->get('organization')->getData());
                $object->setDescription($form->get('description')->getData());
                $object->setCreateDate($form->get('createDate')->getData());
                foreach ($services as $service) {
                    $object->addService($service);
                }
                
            } elseif ($type == 'electronic_trading') {
                $services = $form->get('services')->getData();
                $object = new \Lists\ProjectBundle\Entity\ProjectElectronicTrading();
                $object->setOrganization($form->get('organization')->getData());
                $object->setDescription($form->get('description')->getData());
                $object->setCreateDate($form->get('createDate')->getData());
                foreach ($services as $service) {
                    $object->addService($service);
                }
            }
            $isManager = $object->getOrganization()->isManager($user);
            if ($isManager) {
                $object->setStatusAccess(true);
            } else {
                throw $this->createAccessDeniedException('Уведомление на почту сделать');
            }

            $object->setUserCreated($user);
            $em->persist($object);

            $managerProject = new ManagerProjectType();
            $managerProject->setPart(100);
            $managerProject->setUser($user);
            $managerProject->setProject($object);
            $em->persist($managerProject);

            $em->flush();

            return $this->redirect($this->generateUrl('lists_project_'.$type.'_show', array (
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
        $method = 'canSee'.$this->nameEntity;
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

        $method = 'canSee'.$this->nameEntity;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }
        $methodAll = 'canSeeAll'.$this->nameEntity;
        if ($access->$methodAll()) {
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
            ->getRepository('ListsProjectBundle:Project');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getListProjectForTender($user);

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
}
