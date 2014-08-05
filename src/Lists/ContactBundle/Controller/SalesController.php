<?php

namespace Lists\ContactBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Lists\DepartmentBundle\Entity\Departments;

/**
 * Class SalesController
 */
class SalesController extends BaseController
{
    protected $filterNamespace = 'contacts.sales.filters';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $this->addToFilters('page', $page);

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }

    /**
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationAction($organizationId)
    {
        $this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page', 1);

        $user = $this->getUser();

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($user->getId(), $organizationId);

        if ($organizationId) {
            $departmentContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                ->getMyDepartmentByOrganizationContacts($organizationId);
        } else {
            $departmentContacts = array();
        }

        if (!$organizationId) {
            /** @var \Knp\Component\Pager\Paginator $paginator */
            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $organizationContacts,
                $page,
                20
            );
        } else {
            $pagination = $organizationContacts->getResult();
        }

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'pagination' => $pagination,
            'organizationId' => $organizationId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'departmentContacts' => $departmentContacts
        ));
    }

    /**
     * @param int $departmentId
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentAction($departmentId, $organizationId)
    {
        $this->refreshFiltersIfAjax();

        if (!$organizationId) {
            /** @var Departments $department */
            $department = $this->getDoctrine()->getRepository('ListsDepartmentBundle:Departments')
                ->find($departmentId);

            $organizationId = $department->getOrganizationId();
        }

        if ($organizationId && $departmentId) {
            $departmentContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                ->getMyContactsByDepartmentId($departmentId)
                ->getResult();
        } else {
            $departmentContacts = array();
        }

        $pagination = array();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'pagination' => $pagination,
            'organizationId' => $organizationId,
            'departmentId' => $departmentId,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'departmentContacts' => $departmentContacts
        ));
    }

    /**
     * @param int $id
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationElementAction($id, $organizationId)
    {
        $organizationContactQuery = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts(array(), null, $id);

        $organizationContact = $organizationContactQuery->getSingleResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
            ));
    }

    /**
     * @param int $id
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentOrganizationElementAction($id, $organizationId)
    {
        $organizationContactQuery = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->getMyDepartmentByOrganizationContactsQuery(null, $id);

        $organizationContact = $organizationContactQuery->getSingleResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
                'modelName' => ModelContactRepository::MODEL_DEPARTMENT
            ));
    }

    /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handlingAction($handlingId)
    {
        $user = $this->getUser();

        $organizationId = $this->getDoctrine()->getManager()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getOrganizationByHandlingId($handlingId);

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($user->getId(), $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
            'pagination' => $handlingContacts,
            'handlingId' => $handlingId,
            'organizationId' => $organizationId,
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

        if ($isAjax) {
            $this->removeFromFilters('page');
        }
    }
}
