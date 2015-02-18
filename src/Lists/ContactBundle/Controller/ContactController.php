<?php

namespace Lists\ContactBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Lists\ContactBundle\Entity\ModelContactRepository;

/**
 * Class ContactController
 */
class ContactController extends BaseController
{
    protected $filterNamespace = 'contacts.sales.filters';

    /**
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectAction($id)
    {
        $project = $this->getDoctrine()->getManager()->getRepository('ListsProjectBundle:Project')->find($id);
        $organizationId = $project->getOrganization()->getId();
        $contacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getContactsForOrganization($organizationId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:Contact:organization.html.twig', array(
            'pagination' => $contacts,
            'handlingId' => $id,
            'organizationId' => $organizationId
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

        return $this->render('ListsContactBundle:Contact:organization.html.twig', array(
            'pagination' => $handlingContacts,
            'handlingId' => $handlingId,
            'organizationId' => $organizationId
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

        return $this->render('ListsContactBundle:Contact:organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId
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

        return $this->render('ListsContactBundle:Contact:organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId,
                'modelName' => ModelContactRepository::MODEL_DEPARTMENT
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

        return $this->render('ListsContactBundle:Contact:organization.html.twig', array(
            'pagination' => $pagination,
            'organizationId' => $organizationId,
            'departmentContacts' => $departmentContacts
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
