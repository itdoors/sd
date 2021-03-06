<?php

namespace Lists\ContactBundle\Controller;

/**
 * Class SalesDispatcherController
 */
class SalesDispatcherController extends SalesController
{
    protected $filterNamespace = 'contacts.sales.dispatcher.filters';
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

    /**
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationAction($organizationId)
    {
        $this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page', 1);

        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($teamUserIds, $organizationId)
            ->getResult();

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
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handlingAction($handlingId)
    {
        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($teamUserIds, $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':handling.html.twig', array(
                'handlingContacts' => $handlingContacts,
                'handlingId' => $handlingId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
}
