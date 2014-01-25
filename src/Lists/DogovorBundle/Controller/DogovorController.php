<?php

namespace Lists\DogovorBundle\Controller;

use SD\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class DogovorController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.filters';
    protected $filterFormName = 'dogovorFilterForm';
    protected $baseRoute = 'lists_dogovor_dogovor_index';
    protected $baseRoutePrefix = 'dogovor';
    protected $baseTemplate = 'Dogovor';

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
     * Executes new action
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('dogovorForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var \Lists\HandlingBundle\Entity\Handling $object */
            /*$object = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_sales_handling_show', array(
                'id' => $object->getId()
            )));*/
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
     */
    public function showAction($id)
    {
        /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($id);

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'dogovor' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
