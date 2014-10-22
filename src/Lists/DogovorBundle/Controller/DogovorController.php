<?php

namespace Lists\DogovorBundle\Controller;

use Lists\DogovorBundle\Entity\DogovorRepository;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DogovorController
 */
class DogovorController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.filters';
    protected $filterFormName = 'dogovorFilterForm';
    protected $baseRoute = 'lists_dogovor_dogovor_index';
    protected $baseRoutePrefix = 'dogovor';
    protected $baseTemplate = 'Dogovor';
     /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * indexAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor');

        /** @var \Doctrine\ORM\Query */
        $query = $repository->getAllForDogovorQuery($this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate. ':index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * listDangerAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDangerAction()
    {
        $baseFilter = $this->container->get('it_doors_ajax.base_filter_service');
     
        $namespace = 'Danger';

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor');

        /** @var \Doctrine\ORM\Query */
        $items = $repository->getAllDanger();
        $entities = $items['entities'];
        $count = $items['count'];
        
        $page = $baseFilter->getPaginator($namespace);
        if (!$page) {
            $page = 1;
        }
        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);

        return $this->render('ListsDogovorBundle:Dogovor:listDanger.html.twig', array(
            'pagination' => $pagination,
            'namespace' => $namespace,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
    /**
     * Renders single element of dogovor list
     *
     * @param int $id
     *
     * @return string
     */
    public function elementDangerAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $itemQuery = $dr->getAllForDogovorQuery(array(), $id);

        $item = $itemQuery->getSingleResult();

        return $this->render('ListsDogovorBundle:Dogovor:elementDanger.html.twig', array(
            'item' => $item,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('dogovorForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
            $object = $form->getData();

            $file = $form['file']->getData();

            if ($file) {
                $object->upload();
            }

            $object->setUser($user);
            $object->setCreateDateTime(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_dogovor_dogovor_show', array(
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':new.html.twig', array(
            'form' => $form->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate
        ));
    }

    /**
     * Executes show action
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        /** @var DogovorRepository $dogovorRepository */
        $dogovorRepository = $this->get('lists_dogovor.repository');

        /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
        $object = $dogovorRepository
            ->getDogovorById($id);

        $object['isActiveChoices'] = $dogovorRepository->getIsActiveChoices();
        $object['prolongationChoices'] = $dogovorRepository->getProlongationChoices();
        $object['mashtabChoices'] = $dogovorRepository->getMashtabChoices();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'dogovor' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }

    /**
     * Renders single element of dogovor list
     *
     * @param int $id
     *
     * @return string
     */
    public function elementAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $itemQuery = $dr->getAllForDogovorQuery(array(), $id);

        $item = $itemQuery->getSingleResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':element.html.twig', array(
            'item' => $item,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
    /**
     * Renders single element of dogovor list
     *
     * @param integer $id Organization.id
     *
     * @return string
     */
    public function forOrganizationAction($id)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->get('lists_dogovor.repository');

        $dogovors = $dr->getDogovorByOrganizationCustomerPerformerId($id);

        return $this->render('ListsDogovorBundle:Dogovor:forOrganization.html.twig', array(
            'dogovors' => $dogovors
        ));
    }
}
