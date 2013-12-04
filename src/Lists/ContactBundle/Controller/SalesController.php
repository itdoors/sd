<?php

namespace Lists\ContactBundle\Controller;

use SD\CommonBundle\Controller\BaseFilterController as BaseController;

class SalesController extends BaseController
{
    protected $filterNamespace = 'contacts.sales.filters';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $this->addToFilters('page', $page);

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    public function organizationAction($organizationId)
    {
        $this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page');

        $user = $this->getUser();

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($user->getId(), $organizationId);

        if (!$organizationId)
        {
            /** @var \Knp\Component\Pager\Paginator $paginator */
            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $organizationContacts,
                $page,
                20
            );
        }
        else
        {
            $pagination = $organizationContacts->getResult();
        }

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'pagination' => $pagination,
            'organizationId' => $organizationId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    public function handlingAction($handlingId)
    {
        $user = $this->getUser();

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($user->getId(), $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':handling.html.twig', array(
            'handlingContacts' => $handlingContacts,
            'handlingId' => $handlingId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    /**
     * If ajax request we need to remove $page var from filters
     */
    public function refreshFiltersIfAjax()
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->get('request');

        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax)
        {
            $this->removeFromFilters('page');
        }
    }
}
