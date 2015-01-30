<?php

namespace Lists\HandlingBundle\Controller;

use Lists\HandlingBundle\Controller\ProjectController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\HandlingBundle\Entity\HandlingUser;

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
        $method = 'canSee'.$this->nameConroller;
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

        /** @var \Lists\HandlingBundle\Entity\ProjectGosTenderRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Project'.$this->nameConroller);

        $methodRepository = 'getList'.$this->nameConroller;
        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->$methodRepository($status);

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
}
